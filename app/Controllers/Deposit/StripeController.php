<?php

namespace App\Controllers\Deposit;

use App\Config\RestConfig;
use App\Libraries\Stripe;
use App\Log\AppLogger;
use App\Log\TransactionLogger;
use App\Middleware\Auth;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class StripeController extends ResourceController
{
    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;
    private TransactionsModel $transaction;
    protected $stripe;
    private WalletModel $wallet;

    public function __construct()
    {
        $this->auth         = new Auth();
        $this->userModel    = new UsersModel();
        $this->transaction  = new TransactionsModel();
        $this->stripe       = new Stripe();
        $this->wallet       = new WalletModel();
        // This is your real test secret API key.
        \Stripe\Stripe::setApiKey(stripeSecretKey);
    }

    public function supportedCurrency()
    {
        if ($this->auth->check())
        {
            return $this->respond([
                'status'    => true,
                'message'   => 'Currency found',
                'data'      => (new RestConfig())->allowed_currency
            ]);
        }

        return $this->failUnauthorized();
    }

    public function createPaymentIntent()
    {
        if ($this->auth->check())
        {
            //just stretch the data here......
            $email          = $this->auth->Users->email;
            $first_name     = $this->auth->Users->first_name;
            $last_name      = $this->auth->Users->last_name;
            $user_id        = $this->auth->Users->id;

            try {
                // retrieve JSON from POST body
                $json_str       = file_get_contents('php://input');
                $json_obj       = json_decode($json_str);
                $customer_id    = null;

                //Manage Customer
                if ($customer_id == NULL)
                {
                    $customer = \Stripe\Customer::create([
                        'email' => $email,
                        'metadata' => [
                            'firstname'     => $first_name,
                            'lastname'      => $last_name,
                            'user_id'       => $user_id,
                        ],
                    ]);
                    $customer_id = $customer->id;
                }
                else
                {
                    $customer = \Stripe\Customer::update(
                        $customer_id,
                        [
                            'email' => $email,
                            'metadata' => [
                                'firstname'     => $first_name,
                                'lastname'      => $last_name,
                                'user_id'       => $user_id,
                            ],
                        ]
                    );
                }

                if ($customer_id)
                {
                    //Create the payment Intent
                    $metadata = [
                        "first_name"    => $first_name,
                        'lastname'      => $last_name,
                        "product_name"  => 'wallet',
                    ];

                    //check daily deposit limit!!!
                    if ($this->auth->getRemainingDailyDeposit() < (calculateOrderAmount($json_obj->items) / 100))
                    {
                        return $this->fail('You have reached your daily deposit limit');
                    }

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'amount'    => calculateOrderAmount($json_obj->items),
                        'currency'  => getCurrency($json_obj->items),
                        'customer'  => $customer,
                        'metadata'  => $metadata
                    ]);

                    if ($paymentIntent) {
                        //log this paymentIntents
                        $log_id = (new AppLogger())->log([
                            'user_id'           => $user_id,
                            'email'             => $email,
                            'amount'            => (calculateOrderAmount($json_obj->items) / 100),
                            'payment_method'    => 'card',
                            'currency'          => strtoupper(getCurrency($json_obj->items)),
                            'ip_address'        => $this->request->getIPAddress(),
                            'country'           => ip_info($this->request->getIPAddress())['country'],
                            'intent_id'         => $paymentIntent->id,
                            'trans_date'        => date('Y-m-d'),
                        ], new TransactionLogger());

                        if ($log_id) {
                            $output = [
                                'clientSecret' => $paymentIntent->client_secret,
                            ];
                        }

                        return $this->respond($output);
                    }

                } else {
                    return $this->fail(['error' => 'Something went wrong, please try later']);
                }

            } catch (Error $e) {
                http_response_code(500);
                return $this->respond(['error' => $e->getMessage()]);
            }
        }
        return $this->failUnauthorized();
    }

    public function stripePay()
    {

        if ($this->auth->check())
        {

            $paymentIntent = $this->request->getVar('paymentIntent');

            if ($paymentIntent)
            {

                $allowed = $this->transaction->all_allowed_intent();

                if (count($allowed) > 0) {

                    if (in_array($paymentIntent, $allowed))
                    {
                        $intent = \Stripe\PaymentIntent::retrieve($paymentIntent);

                        if ($intent)
                        {
                            $paymentData = $this->transaction->retrieve_payment($paymentIntent);

                            if ($intent->status)
                            {

                                foreach ($intent->charges->data as $data)
                                {
                                    $balance_transaction        = $data['balance_transaction'];
                                    $card_type                  = $data['payment_method_details']->card->brand;
                                    $card_number                = $data['payment_method_details']->card->last4;
                                    $refunded                   = $data['amount_refunded'];
                                    $receipt_url                = $data['receipt_url'];
                                    $error                      = $data['failure_message'];
                                    $currency                   = $data['currency'];
                                    $id                         = $data['id'];
                                }
                                //payment was successful.
                                $amount = ($intent->amount_received / 100);
                                $this->transaction->updateTransaction([
                                    'id'                => $paymentData->id,
                                    'user_id'           => $paymentData->user_id,
                                    'amount'            => $amount,
                                    'reference'         => $balance_transaction,
                                    'card_type'         => $card_type,
                                    'card_number'       => $card_number,
                                    'refunded'          => $refunded,
                                    'receipt_url'       => $receipt_url,
                                    'gateway'           => 'stripe',
                                    'currency'          => $currency,
                                    'transaction_id'    => $id
                                ], $intent->status);

                                if ($intent->status === 'succeeded')
                                {
                                    return $this->respond([
                                        'status' => true,
                                        'result' => [
                                            'receipt_url'   => $receipt_url,
                                            'message'       => 'Payment was successful'
                                        ]
                                    ]);
                                }
                                else
                                {
                                    return $this->fail($error);
                                }
                            }
                        }
                    }
                }
            }
            return $this->fail('Payment Intent not found');

        }

        return $this->failUnauthorized();
    }

}