<?php
namespace App\Middleware;

use App\Models\KycModel;
use App\Models\SettingsModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use App\Services\Token;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;


class Auth extends ResourceController
{

    use ResponseTrait;

    public $Users;

    protected UsersModel $userModel;

    protected Token $token;
    private KycModel $userKyc;
    private TransactionsModel $transactionModel;
    private SettingsModel $settingsModel;

    function __construct(){
        $this->userModel            = new UsersModel();
        $this->token                = new Token();
        $this->userKyc              = new KycModel();
        $this->transactionModel     = new TransactionsModel();
        $this->settingsModel        = new SettingsModel();
    }

    public function guard()
    {
        if ($this->token->GetValidatedToken())
        {
            try {
                $allowed = [];
                $allUsers = $this->userModel->findAll();
                foreach ($allUsers as $user)
                {
                    $allowed[] = $user->id;
                }
                if (in_array($this->token->GetValidatedToken()->data->id,$allowed))
                {
                    return $this->token->GetValidatedToken();
                }
                return $this->failUnauthorized();
            }
            catch (\Exception $e){
                return $this->failUnauthorized();
            }
        }
        else{
            return $this->fail('No Authorization Bearer Code');
        }
    }

    public function check():bool
    {
        try {

            if ($this->token->GetValidatedToken()){

                $allowed        = [];
                $allowedToken   = [];
                $allUsers       = $this->userModel->findAll();

                foreach ($allUsers as $user)
                {
                    $allowed[]      = $user->email;
                    $allowedToken[] = $user->active_token;
                }

                if (in_array($this->token->GetAuthToken(),$allowedToken))
                {

                    if (in_array($this->token->GetValidatedToken()->data->email,$allowed))
                    {

                        $user_id            = $this->userModel->getInfoByEmail($this->token->GetValidatedToken()->data->email)->id;
                        $this->Users        = $this->userModel->find($user_id);

                        return true;
                    }

                }

            }
            return false;
        }

        catch (\Exception $e){
            return false;
        }

    }

    //get User KYC Details if available
    public function user_kyc()
    {
        if ($this->Users)
        {
            return $this->userKyc->getInfo($this->Users->id);
        }

        return null;
    }
    //get today total deposit!!!
    /**
     * @return int
     */
    private function getTodayTotalDeposit():int
    {
        if ($this->Users)
        {
            return $this->transactionModel->todayDepositTotal($this->Users->id);
        }

        return 0;
    }
    private function getUserDailyDepositLimit()
    {
        $userKYC = $this->user_kyc();
        if($userKYC!= null)
        {
           if ($userKYC->approved == 1)
           {
               return $this->settingsModel->getSettings()->daily_deposit_limit;
           }
        }
        return $this->settingsModel->getSettings()->daily_deposit_limit_kyc;
    }
    public function getRemainingDailyDeposit():int
    {
        return ($this->getUserDailyDepositLimit() - $this->getTodayTotalDeposit());
    }


    //get today total withdrawal!!!
    private function getTodayTotalWithdrawal():int
    {
        if ($this->Users)
        {
            return $this->transactionModel->todayWithdrawalTotal($this->Users->id);
        }

        return 0;
    }

    private function getUserDailyWithdrawalLimit()
    {
        $userKYC = $this->user_kyc();
        if($userKYC!= null)
        {
            if ($userKYC->approved == 1)
            {
                return $this->settingsModel->getSettings()->daily_withdrawal_limit_kyc;
            }
        }

        return $this->settingsModel->getSettings()->daily_withdrawal_limit;
    }

    public function getRemainingDailyWithdrawal():int
    {
        return  ($this->getUserDailyWithdrawalLimit() - $this->getTodayTotalWithdrawal());
    }

    public function setToken($token):bool
    {
       $verified_token = $this->token->VerifyToken($token);
        if ($verified_token)
        {
            $this->userModel->set('active_token',$token)->where('email',$verified_token->data->email)->update();
        }
        return false;
    }

    public function app_settings()
    {
        return $this->settingsModel->getSettings();
    }




}