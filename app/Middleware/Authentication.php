<?php


namespace App\Middleware;


use App\Models\UsersModel;
use CodeIgniter\Router\Exceptions\RedirectException;

class Authentication
{

    public $user_data;

    public function __construct()
    {
        $userModel = new UsersModel();

        if (session()->has('user_id') && session()->has('is_login'))
        {
            $this->user_data = $userModel->find(session()->get('user_id'));
        }
        else
        {
            throw new RedirectException(route_to('admin/auth/login'));
        }
    }

}