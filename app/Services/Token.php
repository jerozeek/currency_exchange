<?php
namespace App\Services;

use App\Middleware\Auth;
use App\Models\UsersModel;
use Firebase\JWT\JWT;

class Token
{

    protected $request;

    function __construct(){
        $this->request = service('request');
    }


    public function GetAuthToken(){
        $token = null;
        $headers = $this->request->getServer('HTTP_AUTHORIZATION');
        $arr = explode(" ",$headers);
        $token = $arr[1];
        return $token;
    }

    public function GetValidatedToken(){

        if ($this->GetAuthToken()){
            $validated = JWT::decode($this->GetAuthToken(),KEY,array('HS256'));
            if ($validated){
                return $validated;
            }
        }
        return false;
    }

    public function blacklistToken():bool
    {
        $userModel  = new UsersModel();
        $auth       = new Auth();

        if ($auth->check())
        {
            //current login user data
            $active = $userModel->find($auth->Users->id);
            if ($active->active_token === $this->GetAuthToken())
            {
                $info = $userModel->find($auth->Users->id);
                $info->active_token = null;
                try {
                    $userModel->save($info);
                    return true;
                } catch (\ReflectionException $e) {
                    return false;
                }
            }
            else{
                return false;
            }
        }
        return false;
    }

    public function VerifyToken($token)
    {
       return JWT::decode($token,KEY,array('HS256'));
    }

    public function getJsonData(){
        return $this->request->getJSON('php://input');
    }



}