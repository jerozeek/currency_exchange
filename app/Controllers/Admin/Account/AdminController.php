<?php


namespace App\Controllers\Admin\Account;


use App\Middleware\Authentication;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{

    private Authentication $auth;
    private TransactionsModel $transactionModel;
    private UsersModel $userModel;

    public function __construct()
    {
        $this->auth             = new Authentication();
        $this->transactionModel = new TransactionsModel();
        $this->userModel        = new UsersModel();
    }

    public function dashboard()
    {
        $data = [
            'page_title'        => 'Dashboard-'.app_name,
            'admin_info'        => $this->auth->user_data,
            'transactions'      => $this->transactionModel->orderBy('id','DESC')->findAll(),
            'userModel'         => $this->userModel,
            'users'             => $this->userModel->orderBy('id','DESC')->findAll(),
            'total_deposit'     => $this->transactionModel->where(['status' => 'success', 'transaction_type' => 'deposit'])->get()->getResult(),
            'total_withdrawal'  => $this->transactionModel->where(['status' => 'success', 'transaction_type' => 'transfer'])->get()->getResult(),
        ];

        return view('admin/account/dashboard',$data);
    }

}