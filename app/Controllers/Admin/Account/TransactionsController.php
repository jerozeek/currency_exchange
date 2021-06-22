<?php


namespace App\Controllers\Admin\Account;


use App\Middleware\Authentication;
use CodeIgniter\Controller;

class TransactionsController extends Controller
{

    private Authentication $auth;

    public function __construct()
    {
        $this->auth = new Authentication();
    }


}