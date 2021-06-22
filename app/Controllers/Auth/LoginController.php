<?php


namespace App\Controllers\Auth;


use App\Middleware\Auth;
use App\Models\UsersModel;
use App\Services\Token;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class LoginController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;

    protected $modelName    = 'App\Models\UsersModel';
    protected $format       = 'json';

    public function __construct()
    {
        $this->auth         = new Auth();
        $this->userModel    = new UsersModel();
    }

    public function doLogin()
    {
        $data           = (new Token())->getJsonData();
        if ($data)
        {

            if (!array_key_exists('email',$data))
            {
                return $this->fail('Email is required');
            }

            if (!array_key_exists('password',$data))
            {
                return $this->fail('Password is required');
            }

            $user_data  = $this->model->do_login($data['email'],$data['password']);

            if ($user_data)
            {
                return $this->respond([
                    'status'    => true,
                    'message'   => 'Login was successful',
                    'token'     => $user_data
                ]);
            }
            else
            {
                return $this->fail('Invalid login details');
            }
        }

        return $this->failUnauthorized();
    }

}