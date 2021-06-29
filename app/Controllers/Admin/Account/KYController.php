<?php


namespace App\Controllers\Admin\Account;


use App\Events\OneSignal;
use App\Middleware\Authentication;
use App\Models\KycModel;
use App\Models\NotificationsModel;
use App\Models\PermissionsModel;
use CodeIgniter\Controller;
use Fluent\Auth\Models\UserModel;

class KYController extends Controller
{

    private Authentication $auth;
    private UserModel $userModel;
    private KycModel $kycModel;
    private PermissionsModel $permissionModel;
    private string $permission  = 'kyc';
    private string $kyc         = 'kyc';
    private string $action      = 'action';
    private string $details     = 'details';
    private NotificationsModel $notify;

    public function __construct()
    {
        $this->auth             = new Authentication();
        $this->userModel        = new UserModel();
        $this->kycModel         = new KycModel();
        $this->permissionModel  = new PermissionsModel();
        $this->notify           = new NotificationsModel();
    }

    public function index()
    {
        if (!$this->permissionModel->hasPermission($this->permission,$this->kyc,$this->auth->user_data->id))
        {
            return view('admin/account/access');
        }

        $allowed    = ['pending','approved'];
        $state      = $this->request->getGet('status');

        if (!$state)
        {
            return view('admin/account/not_found');
        }

        if (!in_array($state,$allowed))
        {
            return view('admin/account/not_found');
        }

        $status = 0;

        if ($state == 'approved')
        {
            $status = 1;
        }
        elseif ($state == 'pending')
        {
            $status = 0;
        }

        $data = [
            'page_title'        => strtoupper($this->kyc).'-'.app_name,
            'admin_info'        => $this->auth->user_data,
            'kyc'               => $this->kycModel->where(['approved' => $status])->orderBy('id','DESC')->get()->getResult(),
            'userModel'         => $this->userModel,
            'permission'        => $this->permissionModel->hasPermission($this->permission,$this->action,$this->auth->user_data->id),
        ];

        return view('admin/account/kyc/kyc',$data);
    }

    public function details($id)
    {
        if (!$this->permissionModel->hasPermission($this->permission,$this->details,$this->auth->user_data->id))
        {
            return view('admin/account/access');
        }

        if (is_numeric($id) && $id !== 0)
        {
            $data = [
                'page_title'        => strtoupper($this->details).'-'.app_name,
                'admin_info'        => $this->auth->user_data,
                'kyc'               => $this->kycModel->find($id),
            ];

            return view('admin/account/kyc/kyc_details',$data);
        }

    }

    public function handleRequest()
    {
        if (!$this->permissionModel->hasPermission($this->permission,$this->action,$this->auth->user_data->id))
        {
            return 'error';
        }
        $id         = $this->request->getGet('id');
        $status     = $this->request->getGet('status');
        $allowed    = [1,2];

        $kycDetails = $this->kycModel->find($id);
        if ($kycDetails)
        {
            if (in_array($status,$allowed))
            {
                if ($status == 1)
                {
                    $message                = 'Your KYC have been approved successfully and you deposit and withdrawal amount have been increased';
                    $kycDetails->approved   = $status;
                    $this->kycModel->save($kycDetails);
                }
                else
                {
                    $message = 'Your KYC was declined. Please login and resubmit your KYC';
                    $this->kycModel->delete($id);
                }

                //send a push notification to clients
                $receiversInfo = $this->userModel->find($kycDetails->user_id);
                $this->notify->setMessage($receiversInfo->player_id,$receiversInfo->id,$message);
            }
            if ($status == 1)
            {
                echo 'approved';
            }
            else
            {
                echo 'declined';
            }
        }
        else
        {
            echo 'error';
        }
    }

}