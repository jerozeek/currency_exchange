<?php


namespace App\Controllers\Auth;

use App\Middleware\Auth;
use App\Models\UsersModel;
use App\Services\Token;
use CodeIgniter\RESTful\ResourceController;

class JwtToken extends ResourceController
{

    protected string $token;

    protected UsersModel $userModel;

    function __construct(){
        helper(['hash_helper','validation_helper']);
        $this->userModel = new UsersModel();
    }


   public function blacklist(){
        $token = new Token();
        if ($token->blacklistToken()){
            return $this->respond(['status' => true,'message' => 'User have successfully logout']);
        }
        return $this->failUnauthorized();
   }

   public function getPlayerID()
   {
       $auth = new Auth();

       if ($auth->check())
       {
           $player_id = $this->request->getGet('player_id');

           if ($player_id)
           {
               $this->userModel->updatePlayerID($auth->Users->id,$player_id);
           }
       }
   }
}