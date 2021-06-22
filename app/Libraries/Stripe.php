<?php
namespace App\Libraries;

use App\Models\TransactionsModel;
use App\Models\WalletModel;
use App\Services\StripeGateway;
use Stripe\Exception\ApiErrorException;


class Stripe implements StripeGateway
{

    public $rest;
    /**
     * @var TransactionsModel
     */
    private TransactionsModel $transactionsModel;
    /**
     * @var WalletModel
     */
    private WalletModel $wallet;

    function __construct(){
        $this->transactionsModel = new TransactionsModel();
        $this->wallet = new WalletModel();
        \Stripe\Stripe::setApiKey(stripeSecretKey);
    }

    public function startTransaction($data,$log)
    {
        try {
            $transaction = \Stripe\Charge::create($data);

            //json serialize the response....
            $chargeJson = $transaction->jsonSerialize();

            if ($chargeJson){
                if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1 && $chargeJson['status'] == 'succeeded'){
                    if ($log){
                        $this->paymentSuccessful([
                            'user_id'          => $log['user_id'],
                            'id'               => $log['log_id'],
                            'transaction_id'   => $chargeJson['balance_transaction'],
                            'amount_paid'      => $chargeJson['amount'],
                            'paidAmount'       => ($chargeJson['amount']/10000),
                            'paidCurrency'     => $chargeJson['currency'],
                        ],$chargeJson['status']);
                        return 'successful';
                    }
                }

                else if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1 && $chargeJson['status'] == 'failed'){
                    $this->transactionsModel->updateTransaction([
                        'user_id'          => $log['user_id'],
                        'id'               => $log['log_id'],
                        'transaction_id'   => $chargeJson['balance_transaction'],
                    ],$chargeJson['status']);
                    return 'failed';
                }

                else if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1 && $chargeJson['status'] == 'pending'){
                    $this->transactionsModel->updateTransaction([
                        'user_id'          => $log['user_id'],
                        'id'               => $log['log_id'],
                        'transaction_id'   => $chargeJson['balance_transaction'],
                    ],QUEUED);

                    return 'pending';
                }
            }else{
                return 'error';
            }
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    public function create_customer($data)
    {
        try {
            return \Stripe\Customer::create($data);
        } catch (ApiErrorException $e) {
            return false;
        }
    }

    public function paymentSuccessful($data,$status)
    {
        if ($data['id']){
            $this->transactionsModel->updateTransaction($data,$status);
            //update the wallet balance via dollar
            $this->wallet->updateBalance($data['user_id'],$data['paidAmount']);
        }
    }

    public function handle()
    {
        // TODO: Implement processCardPayment() method.
    }

    public function paymentQueued()
    {
        // TODO: Implement paymentQueued() method.
    }

    public function paymentFailed()
    {
        // TODO: Implement paymentFailed() method.
    }

    public function cardDecline()
    {
        // TODO: Implement cardDecline() method.
    }

    public function refund()
    {
        // TODO: Implement refund() method.
    }

    public function retrieve($transaction_id)
    {
        try {
            $transaction = \Stripe\Charge::retrieve($transaction_id);

            if ($transaction && $transaction->status === 'succeeded'){
               $currency = $transaction->currency;
            }

        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}