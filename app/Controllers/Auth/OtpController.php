<?php


namespace App\Controllers\Auth;


use App\Events\Notifications;
use App\Middleware\Auth;
use App\Models\UsersModel;
use App\Services\Token;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class OtpController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;

    public function __construct()
    {
        $this->auth         = new Auth();
        $this->userModel    = new UsersModel();
    }

    public function verifyOtp()
    {
        if ($this->auth->check())
        {
            $data = $this->request->getJSON('php://input');

            if (!array_key_exists('otp',$data))
            {
                return $this->fail('OTP is required');
            }

            if ($this->userModel->compareExpire($this->auth->Users->id))
            {
                if ($this->userModel->compareOTP($data['otp'],$this->auth->Users->id))
                {
                    return $this->respond([
                        'status'    => true,
                        'message'   => 'Account verified successfully',
                        'token'     => $this->userModel->regeneratedToken($this->auth->Users->id)
                    ]);
                }

                return $this->fail('Invalid OTP');
            }

            return $this->fail('OTP have expired. Please try logging');
        }

        return $this->failUnauthorized();
    }

    public function doReset()
    {
        if ($this->auth->check())
        {
            $data = $this->request->getJSON('php://input');

            if (!array_key_exists('otp',$data))
            {
                return $this->fail('OTP is required');
            }

            if ($this->userModel->compareExpire($this->auth->Users->id))
            {
                if ($this->userModel->comparePasswordResetOTP($data['otp'],$this->auth->Users->id))
                {
                    return $this->respond([
                        'status'    => true,
                        'message'   => 'OTP verified successfully',
                        'token'     => $this->userModel->regeneratedToken($this->auth->Users->id)
                    ]);
                }

                return $this->fail('Invalid OTP');
            }

            return $this->fail('OTP have expired. Please try logging');
        }

        return $this->failUnauthorized();
    }

    public function doResend()
    {
        helper(['hash_helper']);
        if ($this->auth->check())
        {
            //reset the expire!!!
            $this->userModel->resetExpire($this->auth->Users->id);

            //reset the OTP
            $otp = random_number(4);
            $this->userModel->resetOTPs($otp,$this->auth->Users->id);
            (new Notifications(['otp' => $otp, 'email' => $this->auth->Users->email]))->sendOTPForPasswordReset();

            return $this->respond([
                'status'    => true,
                'message'   => 'OTP resend successfully'
            ]);
        }

        return $this->failUnauthorized();
    }


    public function updatePassword()
    {
        if ($this->auth->check())
        {
            $data = (new Token())->getJsonData();

            if (!array_key_exists('password',$data))
            {
                return $this->fail('Password is required');
            }
            if (!array_key_exists('confirm_password',$data))
            {
                return $this->fail('Confirm password is required');
            }

            if (strlen($data['password']) < 6)
            {
                return $this->fail('Minimum password length is 6');
            }

            if ($data['password'] !== $data['confirm_password'])
            {
                return $this->fail('Confirm password do not match password');
            }

            //update the password now!!
            $this->userModel->updatePassword($data['password'],$this->auth->Users->id);

            //blacklist the token
            (new Token())->blacklistToken();

            return $this->respond([
                'status'    => true,
                'message'   => 'Password updated successfully',
            ]);

        }
    }

}