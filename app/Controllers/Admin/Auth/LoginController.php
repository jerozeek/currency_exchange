<?php


namespace App\Controllers\Admin\Auth;


use App\Models\UsersModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{

    private UsersModel $userModel;

    public function __construct()
    {
        $this->userModel = new UsersModel();
    }

    public function index()
    {
        $data = [
            'page_name'     => 'Administrative Login',
        ];

        return view('admin/auth/login',$data);
    }

    public function adminLogin()
    {
        if ($this->request->withMethod('POST'))
        {

            $email          = $this->request->getPost('email');
            $password       = $this->request->getPost('password');

            $validate       = $this->validate([
                'email'     => 'required|valid_email',
                'password'  => 'required',
            ]);

            if ($validate)
            {

                if ($this->validator->showError('email'))
                {
                    return redirect()->back()->withInput()->with('error',$this->validator->showError('email'));
                }

                if ($this->validator->showError('password'))
                {
                    return redirect()->back()->withInput()->with('error',$this->validator->showError('password'));
                }

            }

            $userDetails    = $this->userModel->getInfoByEmail($email);

            if ($userDetails)
            {
                $verifyPassword = password_verify($password,$userDetails->password);

                if ($verifyPassword)
                {
                    if ($userDetails->account_type == 'admin')
                    {

                        if ($userDetails->status == 'deactivated')
                        {
                            return redirect()->back()->withInput()->with('error','Account have been deactivated. Please contact super admin');
                        }

                        $this->setLogin($userDetails->id);
                        return redirect()->to(base_url('admin/account/dashboard'));
                    }
                }
            }

            return redirect()->back()->withInput()->with('error','Invalid Login Credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/auth/login'));
    }

    private function setLogin($user_id)
    {
        session()->set([
            'user_id'   => $user_id,
            'is_login'  => true
        ]);
    }

}