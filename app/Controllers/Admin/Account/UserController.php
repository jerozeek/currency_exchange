<?php


namespace App\Controllers\Admin\Account;


use App\Middleware\Authentication;
use App\Models\PermissionsModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;

class UserController extends Controller
{

    private Authentication $auth;
    private UsersModel $userModel;
    private PermissionsModel $permissionModel;
    private string $permission = 'users';
    private string $view = 'view';
    private string $details = 'details';
    private string $assign_permission = 'permission';
    private string $edit = 'edit';
    private string $transactions = 'transactions';

    public function __construct()
    {
        $this->auth             = new Authentication();
        $this->userModel        = new UsersModel();
        $this->permissionModel  = new PermissionsModel();
    }

    public function index()
    {
        if (!$this->permissionModel->hasPermission($this->permission,$this->view,$this->auth->user_data->id))
        {
            return view('admin/account/access');
        }

        $data = [
            'page_title'        => 'Manage Users - '.app_name,
            'admin_info'        => $this->auth->user_data,
            'users'             => $this->userModel->orderBy('id','DESC')->findAll(),
            'edit'              => $this->permissionModel->hasPermission($this->permission,$this->edit,$this->auth->user_data->id),
            'permission'        => $this->permissionModel->hasPermission($this->permission,$this->assign_permission,$this->auth->user_data->id),
            'transactions'      => $this->permissionModel->hasPermission($this->permission,$this->transactions,$this->auth->user_data->id),
            'details'           => $this->permissionModel->hasPermission($this->permission,$this->details,$this->auth->user_data->id),
        ];

        return view('admin/account/users/customers',$data);
    }

    public function userDetails($id)
    {
        if (!$this->permissionModel->hasPermission($this->permission,$this->details,$this->auth->user_data->id))
        {
            return view('admin/account/access');
        }

        if ($id)
        {
            $userInfo = $this->userModel->find($id);
            if ($userInfo)
            {
                $data = [
                    'page_title'        => 'Manage Users - '.app_name,
                    'admin_info'        => $this->auth->user_data,
                    'details'           => $this->userModel->find($id),
                    'permission'        => $this->permissionModel->hasPermission($this->permission,$this->assign_permission,$this->auth->user_data->id),
                    'transactions'      => $this->permissionModel->hasPermission($this->permission,$this->transactions,$this->auth->user_data->id),
                ];

                return view('admin/account/users/details',$data);
            }
        }

    }

    public function manageStatus()
    {
        $user_id    = $this->request->getGet('user_id');
        $status     = $this->request->getGet('status');
        $userInfo   = $this->userModel->find($user_id);
        if ($userInfo)
        {
            $newStatus = $status == 'active' ? 'deactivated' : 'active';
            $userInfo->status = $newStatus;
            $this->userModel->save($userInfo);
            echo 'success';
        }
        else
        {
            echo 'error';
        }
    }

}