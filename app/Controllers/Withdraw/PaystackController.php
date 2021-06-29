<?php


namespace App\Controllers\Withdraw;


use App\Libraries\Paystack;
use App\Middleware\Auth;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class PaystackController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;
    private Paystack $paystack;
    private WalletModel $wallet;
    private TransactionsModel $transaction;

    public function __construct()
    {
        $this->auth         = new Auth();
        $this->userModel    = new UsersModel();
        $this->paystack     = new Paystack();
        $this->wallet       = new WalletModel();
        $this->transaction  = new TransactionsModel();
        helper(['hash_helper']);
    }

    public function bankList()
    {
        if ($this->auth->check())
        {
            return $this->respondCreated((new Paystack())->supportedBank());
        }

        return $this->failUnauthorized();
    }

    public function validateAccount()
    {
        if ($this->auth->check())
        {
            $bank_code          = $this->request->getGet('bank_code');
            $account_no         = $this->request->getGet('account_number');

            //do account number validation properly
            if (strlen($account_no) == 10 && is_numeric($account_no))
            {
                $data = $this->paystack->verify_account($account_no,$bank_code);

                if ($data)
                {
                    $result = json_decode($data,TRUE);

                    if ($result['status'] == 1)
                    {
                        //data found
                        return $this->respond([
                            'status'    => true,
                            'data'      => [
                                'message'           => $result['message'],
                                'account_number'    => $result['data']['account_number'],
                                'account_name'      => $result['data']['account_name'],
                            ]
                        ]);
                    }

                    return $this->fail($result['message']);
                }

                return $this->fail('Account Details Not Found');
            }

            return $this->fail('Invalid Account Number');
        }

        return $this->failUnauthorized();
    }

    public function makeTransfer()
    {
        if ($this->auth->check())
        {
            $account_name       = $this->request->getPost('account_name');
            $account_number     = $this->request->getPost('account_number');
            $bank_code          = $this->request->getPost('bank_code');
            $wallet             = $this->request->getPost('wallet');
            $amount             = $this->request->getPost('amount');
            $narration          = $this->request->getPost('narration');
            $narration          = $narration == '' ? 'transfer' : $narration;

            if (strlen($account_number) !== 10)
            {
                return $this->fail('Invalid account number');
            }

            if (empty($account_name))
            {
                return $this->fail('Account name is required');
            }

            if (empty($bank_code))
            {
                return $this->fail('bank name is required');
            }

            if (empty($wallet))
            {
                return $this->fail('Please choose wallet');
            }

            if (empty($amount))
            {
                return $this->fail('Amount is required');
            }

            if (min_transfer > $amount)
            {
                return $this->fail('Minimum transfer amount is '. min_transfer);
            }

            if ($amount > max_transfer)
            {
                return $this->fail('Maximum transfer amount is '. max_transfer);
            }

            if ($wallet !== 'naira')
            {
                return $this->fail('Currency not supported');
            }

            //Handle charges
            $charges        = fixed_amount_percentage_charges/100 * $amount;
            $total_debited  = $charges+$amount;
            $walletDetails  = $this->wallet->getWallet($this->auth->Users->id)->$wallet;

            if ($walletDetails < $total_debited)
            {
                return $this->fail('Insufficient funds');
            }

            //check limit!!
            if ($this->auth->getRemainingDailyWithdrawal() < $amount)
            {
                return $this->fail('You have reached your daily transfer limit');
            }


            //get the current bank name
            $bankList = $this->paystack->supportedBank();

            foreach ($bankList->data as $bank)
            {
               if ($bank->code == $bank_code)
               {
                   $bank_name = $bank->name;
               }
            }

            if ($bank_name)
            {

                $dataInfo = $this->paystack->initiate_recipient($account_name,$narration,$account_number,$bank_code);
                if ($dataInfo)
                {
                    $response = json_decode($dataInfo, TRUE);

                    if ($response['status'] == true)
                    {
                        //log the transaction
                        $transaction_id = $this->transaction->create([
                            'user_id'                       => $this->auth->Users->id,
                            'amount'                        => $amount,
                            'charges'                       => $charges,
                            'email'                         => $this->auth->Users->email,
                            'currency'                      => 'NGN',
                            'ip_address'                    => $this->request->getIPAddress(),
                            'transaction_type'              => 'transfer',
                            'transaction'                   => 'in_app',
                            'reference'                     => random_number(12),
                            'gateway'                       => 'paystack',
                            'beneficiary_account_name'      => $account_name,
                            'beneficiary_account_number'    => $account_number,
                            'bank_name'                     => $bank_name,
                            'trans_date'                    => date('Y-m-d'),
                            'created_at'                    => date('Y-m-d H:i:s')
                        ]);

                        if ($transaction_id)
                        {
                            //logger created successfully
                            $processor = $this->paystack->process_withdrawal($amount,$response['data']['recipient_code']);

                            if ($processor)
                            {
                                //debit customer
                                $this->wallet->debitWallet($this->auth->Users->id,$wallet,$total_debited);

                                $result = json_decode($processor, true);

                                if ($result['status'] == true)
                                {
                                    if ($result['data']['status'] == 'success' || $result['data']['status'] == 'pending')
                                    {
                                        //set payment as success
                                        $this->transaction->updateTransactionStatus($transaction_id,$result['data']['status']);

                                        //send an email

                                        return $this->respond([
                                            'status'    => true,
                                            'message'   => 'Transaction was successful'
                                        ]);
                                    }

                                    //do a refund
                                    $this->wallet->fundWallet($this->auth->Users->id,$wallet,$total_debited);
                                }

                                return $this->fail($result['message']);
                            }
                        }
                    }
                }
            }

            return $this->fail('Account could not be resolved. Please try again');
        }

        return $this->failUnauthorized();
    }
}