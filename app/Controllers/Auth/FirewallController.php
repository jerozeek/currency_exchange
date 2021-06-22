<?php


namespace App\Controllers\Auth;


use App\Events\Notifications;
use App\Middleware\Auth;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Model;
use CodeIgniter\RESTful\ResourceController;

class FirewallController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;

    public function __construct()
    {
        $this->auth         = new Auth();
        $this->userModel    = new UsersModel();
    }

    public function runValidations()
    {
        if ($this->auth->check())
        {

            $this->checkWallet($this->auth->Users->id);


            if ($this->auth->Users->status == 'not_activated')
            {
                //reset the expire!!!
                $this->userModel->resetExpire($this->auth->Users->id);

                //send the mail
                $this->resendActivationCode($this->auth->Users->email,$this->auth->Users->activation_code);

                return $this->respond([
                    'status'    => true,
                    'message'   => 'An otp have been sent to your email',
                    'token'     => $this->userModel->regeneratedToken($this->auth->Users->id)
                ],201);
            }

            if ($this->auth->Users->status == 'deactivated')
            {
                return $this->fail('Account have been temporary disabled.',409);
            }

            if ($this->auth->Users->status == 'active')
            {
                $wallet = new WalletModel();
                $info = $wallet->where('user_id',$this->auth->Users->id)->get()->getRow();

                return $this->respond([
                    'status'    => true,
                    'message'   => 'access-granted',
                    'data'      => [
                        'first_name'    => $this->auth->Users->first_name,
                        'last_name'     => $this->auth->Users->last_name,
                        'email'         => $this->auth->Users->email,
                        'profile_image' => $this->auth->Users->profile_image == null ? null : base_url('public/profile/'.$this->auth->Users->profile_image),
                        'wallet'        => [

                            'naira'         => [
                                'sign'      => 'NGN',
                                'currency'  => 'naira',
                                'balance'   => $info->naira,
                                'symbol'    => '₦'
                            ],

                            'dollar'        => [
                                'sign'      => 'USD',
                                'currency'  => 'dollar',
                                'balance'   => $info->dollar,
                                'symbol'    => '$',
                            ],

                            'euro'          => [
                                'sign'      => 'EUR',
                                'currency'  => 'euro',
                                'balance'   => $info->euro,
                                'symbol'    => '€',
                            ],

                            'pound'         => [
                                'sign'      => 'GBP',
                                'currency'  => 'pound',
                                'balance'   => $info->pound,
                                'symbol'    => '£'
                            ],
                        ],
                        'transactions'  => $this->transactionsList($this->auth->Users->id)
                    ]
                ]);
            }
        }

        return $this->failUnauthorized();
    }

    private function resendActivationCode($email,$code)
    {
        (new Notifications(['otp' => $code, 'email' => $email]))->activate_account();
    }

    private function transactionsList($user_id):array
    {
        $transactionsModel = new TransactionsModel();
        return $transactionsModel->fetchTransactions($user_id);
    }


    private function checkWallet($id)
    {
        $wallet = new WalletModel();
        $info = $wallet->where('user_id',$id)->get()->getRow();
        if (!$info)
        {
            $wallet->insert(['user_id' => $id, 'created_at' => date('Y-m-d H:i:s')]);
        }
    }

}