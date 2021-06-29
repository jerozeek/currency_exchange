<?php


namespace App\Controllers\Admin\Account;


use App\Middleware\Authentication;
use App\Models\PermissionsModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;

class TransactionsController extends Controller
{

    private Authentication $auth;
    private UsersModel $userModel;
    private TransactionsModel $transactionModel;
    private PermissionsModel $permissionModel;

    public function __construct()
    {
        $this->auth             = new Authentication();
        $this->userModel        = new UsersModel();
        $this->transactionModel = new TransactionsModel();
        $this->permissionModel  = new PermissionsModel();
    }

    public function transactions()
    {
        $allowed    = ['deposit','transfer','exchange','withdrawal'];
        $type       = $this->request->getGet('type');

        if (in_array($type,$allowed))
        {
            $data = [
                'page_title'        => 'Manage Transactions - '.app_name,
                'admin_info'        => $this->auth->user_data,
                'transactions'      => $this->transactionModel,
                'userModel'         => $this->userModel,
            ];

            return view('admin/account/transactions/index',$data);
        }

        return view('admin/account/not_found');
    }

    public function transactionInvoice($transaction_id)
    {

    }


}