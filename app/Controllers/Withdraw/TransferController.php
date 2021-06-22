<?php


namespace App\Controllers\Withdraw;


use App\Middleware\Auth;
use App\Models\NotificationsModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class TransferController extends ResourceController
{

    use ResponseTrait;

    private UsersModel $userModel;
    private Auth $auth;
    private TransactionsModel $transaction;
    private WalletModel $wallet;
    private NotificationsModel $notify;

    public function __construct()
    {
        $this->userModel    = new UsersModel();
        $this->auth         = new Auth();
        $this->transaction  = new TransactionsModel();
        $this->wallet       = new WalletModel();
        $this->notify       = new NotificationsModel();
        helper(['hash_helper']);
    }

    public function getAllUsers()
    {
        if ($this->auth->check())
        {
            $userInfo   = $this->userModel->findAll();
            $output     = [];

            if ($userInfo)
            {
                foreach ($userInfo as $user)
                {
                    $output[]   = [
                        'id'                => $user->id,
                        'first_name'        => $user->first_name,
                        'last_name'         => $user->last_name,
                        'email'             => $user->email,
                        'profile'           => $user->profile_image == null ? null : base_url("public/images/$user->profile_image")
                    ];
                }

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Users found',
                    'data'      => $output
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

    public function moveFunds()
    {
        if ($this->auth->check())
        {
            $email      = $this->request->getPost('email');
            $fullname   = $this->request->getPost('fullname');
            $wallet     = $this->request->getPost('wallet');
            $amount     = $this->request->getPost('amount');

            $allUsers   = $this->userModel->findAll();
            $allowed[]  = [];

            foreach ($allUsers as $user)
            {
                $allowed[]  = $user->email;
            }

            if (in_array($email,$allowed))
            {
                if (in_app_min > $amount)
                {
                    return $this->fail("Minimum transfer amount is ". in_app_min);
                }

                if ($amount > in_app_max)
                {
                    return $this->fail("Maximum transfer amount is ". in_app_max);
                }

                //handle charges
                $charges        = in_app_charges/100 * $amount;
                $total          = $charges+$amount;
                $walletDetails  = $this->wallet->getWallet($this->auth->Users->id)->$wallet;

                if ($walletDetails < $total)
                {
                    return $this->fail('Insufficient funds');
                }

                //check limit!!
                if ($this->auth->getRemainingDailyWithdrawal() < $amount)
                {
                    return $this->fail('You have reached your daily transfer limit');
                }

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
                    'gateway'                       => 'in_app',
                    'beneficiary_email'             => $email,
                    'beneficiary_fullname'          => $fullname,
                    'trans_date'                    => date('Y-m-d'),
                    'created_at'                    => date('Y-m-d H:i:s')
                ]);

                if ($transaction_id)
                {
                    //debit customer
                    $this->wallet->debitWallet($this->auth->Users->id,$wallet,$total);

                    //fund customer
                    $receiversInfo      = $this->userModel->where(['email' => $email])->get()->getRow();
                    if ($receiversInfo)
                    {
                        //fund receiver
                        $this->wallet->fundWallet($receiversInfo->id,$wallet,$amount);

                        //change transaction status to success
                        $this->transaction->updateTransactionStatus($transaction_id,'success');


                        //log the receivers details
                        $this->transaction->create([
                            'user_id'                       => $receiversInfo->id,
                            'amount'                        => $amount,
                            'charges'                       => 0,
                            'currency'                      => 'NGN',
                            'ip_address'                    => $this->request->getIPAddress(),
                            'transaction_type'              => 'deposit',
                            'transaction'                   => 'in_app',
                            'reference'                     => random_number(12),
                            'gateway'                       => 'in_app',
                            'status'                        => 'success',
                            'sender_name'                   => $this->auth->Users->first_name . ' '. $this->auth->Users->last_name,
                            'created_at'                    => date('Y-m-d H:i:s')
                        ]);

                        $message = 'Your '.$wallet.' wallet account have been created with the sum of '. $amount. ' by '. $this->auth->Users->first_name. ' '. $this->auth->Users->first_name;
                        //send a direct notification to user
                        $this->notify->setMessage($receiversInfo->player_id,$receiversInfo->id,$message);

                        return $this->respond([
                            'status'    => true,
                            'message'   => 'Transfer was successful'
                        ]);
                    }

                    //refund the sender since the receiver was not verified.
                    $this->wallet->fundWallet($this->auth->Users->id,$wallet,$total);

                    return $this->fail('Beneficiary not found');
                }

            }

            return $this->fail('Account not found');
        }

        return $this->failUnauthorized();
    }

}