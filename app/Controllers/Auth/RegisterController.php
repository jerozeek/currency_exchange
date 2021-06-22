<?php


namespace App\Controllers\Auth;


use App\Events\Notifications;
use App\Models\UsersModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class RegisterController extends ResourceController
{
    use ResponseTrait;

    protected $modelName    = 'App\Models\UsersModel';
    protected $format       = 'json';

    private UsersModel $userModel;
    private WalletModel $walletModel;

    public function __construct()
    {
        $this->userModel    = new UsersModel();
        helper(['hash_helper','validation_helper']);
    }

    public function create()
    {
        $data = $this->request->getJSON('php://input');

        if ($data)
        {
            if (!array_key_exists('country',$data))
            {
                return $this->fail('Please choose country');
            }
            $code                       = random_number(4);
            $data['ip_address']         = $this->request->getIPAddress();
            $data['password']           = password_hash($data['password'],PASSWORD_DEFAULT);
            $data['activation_code']    = $code;
            $data['expire']             = strtotime('+30 minutes');

            $token = $this->model->do_signup($data);

            if ($this->model->errors())
            {
                return $this->fail($this->model->errors());
            }

            if ($token)
            {

                //send a welcome Email
                (new Notifications(['otp' => $code, 'email' => $data['email']]))->activate_account();
                return $this->respond([
                    'status'    => true,
                    'message'   => 'Account created successfully',
                    'token'     => $token
                ]);
            }
            else
            {
                return $this->fail('Something went wrong');
            }
        }

        return $this->failNotFound();
    }

}