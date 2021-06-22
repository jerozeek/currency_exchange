<?php


namespace App\Controllers\Auth;

use App\Events\Notifications;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class ForgetPasswordController extends ResourceController
{

    use ResponseTrait;

    private UsersModel $userModel;

    public function __construct()
    {
        $this->userModel     = new UsersModel();
    }

    public function doSearch()
    {
        $email  = $this->request->getGet('email');

        if ($this->userModel->verifyEmail($email))
        {
            $accountDetails = $this->userModel->where('email',$email)->get()->getRow();
            $this->userModel->resetExpire($accountDetails->id);

            (new Notifications(['otp' => $accountDetails->activation_code, 'email' => $email]))->sendOTPForPasswordReset();

            return $this->respond([
                'status'    => true,
                'message'   => 'An OTP have been sent to your email.',
                'token'     => $this->userModel->regeneratedToken($accountDetails->id)
            ]);

        }

        return $this->failNotFound();
    }

}