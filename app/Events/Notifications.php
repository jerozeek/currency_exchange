<?php


namespace App\Events;


class Notifications
{
    public array $data = [];

    private \CodeIgniter\Email\Email $emailHandler;

    public function __construct($data)
    {
        $this->emailHandler = \Config\Services::email();
        $this->data = $data;
    }

    public function activate_account():bool
    {
        if (count($this->data))
        {
            $this->emailHandler->setTo($this->data['email']);
            $this->emailHandler->setSubject('Account Activation');
            $this->emailHandler->setMessage(view('emails/activate_account',$this->data));
            $this->emailHandler->send();
            if ($this->emailHandler->send()){
                return true;
            }
            return false;
        }
    }

    public function sendOTPForPasswordReset():bool
    {
        if (count($this->data) > 0)
        {
            $this->emailHandler->setTo($this->data['email']);
            $this->emailHandler->setSubject('Password Reset OTP');
            $this->emailHandler->setMessage(view('emails/password_reset',$this->data));
            if ($this->emailHandler->send()){
                return true;
            }
            return false;
        }
    }
}