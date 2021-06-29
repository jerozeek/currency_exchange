<?php

namespace App\Models;

use App\Entities\UsersEntity;
use App\Middleware\Auth;
use CodeIgniter\Model;
use Firebase\JWT\JWT;

class UsersModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'users';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = UsersEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'first_name','last_name','email','password','phone','ip_address','country','status','activation_code','active_token',
        'player_id','profile_image','created_at','updated_at','deleted_at','last_login','login_count','expire','account_type',
    ];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [
        'email'             => 'required|valid_email|is_unique[users.email,id,{id}]',
        'first_name'        => 'required|alpha_numeric_punct|min_length[3]',
        'last_name'         => 'required|alpha_numeric_punct|min_length[3]',
        'phone'             => 'required|min_length[11]|max_length[20]|is_unique[users.phone,id,{id}]',
        'password'          => 'required|min_length[6]',
        'country'           => 'required'
    ];
	protected $validationMessages   = [
        'email'        => [
            'is_unique' => 'Email already exist'
        ],
        'phone'        => [
            'is_unique' => 'Phone number already exist'
        ]
    ];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

    public function getInfoByEmail($email)
    {
        return $this->where('email',$email)->get()->getRow();
    }

    public function updatePlayerID($user_id,$player_id)
    {
        $user = $this->find($user_id);
        $user->player_id = $player_id;
        $this->save($user);
    }

    public function do_signup($data){
        try {

            $id = $this->insert($data);

            if (is_numeric($id)){
                $raw_data           = $this->find($id);
                $generatedToken     = $this->GenerateJWT([
                    'email'         => $raw_data->email,
                    'first_name'    => $raw_data->first_name,
                    'login_state'   => password_hash(time(),PASSWORD_BCRYPT)
                ]);

                //generate wallet
                $walletModel  = new WalletModel();
                $walletModel->insert(['user_id' => $id, 'created_at' => date('Y-m-d H:i:s')]);

                //set to active token
                (new Auth())->setToken($generatedToken);

                //return generated token
                return $generatedToken;
            }
        }
        catch (\ReflectionException $e) {
            return $this->fail($e->getMessage());
        }
    }

    private function GenerateJWT($data):String
    {
        $token = array(
            "iss" => iss,
            "aud" => aud,
            "iat" => iat,
            "nbf" => nbf,
            "data" => $data
        );
        return JWT::encode($token, KEY);
    }

    public function compareExpire(int $user_id):bool
    {
        $now    = strtotime('now');
        $user   = $this->find($user_id);
        if ($user->expire >= $now)
        {
            return true;
        }

        return false;
    }

    public function compareOTP($otp, $id):bool
    {
        helper(['hash_helper']);

        $user   = $this->find($id);
        if ($user->activation_code == $otp)
        {
            $code = random_number(4);
            self::resetOTP($id,$code);
            return true;
        }
        return false;
    }

    public function comparePasswordResetOTP($otp, $id):bool
    {
        helper(['hash_helper']);
        $code   = random_number(4);
        $user   = $this->find($id);
        if ($user->activation_code === $otp)
        {
           $this->resetOTPs($code,$id);
            return true;
        }
        return false;
    }

    private function resetOTP($user_id,$code)
    {
        $user = $this->find($user_id);
        $user->activation_code  = $code;
        $user->status           = 'active';
        $this->save($user);
    }

    public function regeneratedToken($user_id):string
    {
        $state = $this->find($user_id);
        $generatedToken = $this->GenerateJWT([
            'email'             => $state->email,
            'first_name'        => $state->first_name,
            'login_state'       => password_hash(time(),PASSWORD_BCRYPT)
        ]);
        //set the token as the active user Token....
        (new Auth())->setToken($generatedToken);
        return $generatedToken;
    }


    public function do_login($email,$password)
    {
        $state = $this->where('email',$email)->get()->getRow();
        if ($state)
        {
            if (password_verify($password,$state->password))
            {
                //generate the JWT token
                $user               = $this->find($state->id);
                $user->last_login   = date('Y-m-d H:i:s');
                $user->login_count  = $user->login_count + 1;
                try {
                    $this->save($user);
                    //reset the token and assign to user.
                    return $this->regeneratedToken($state->id);
                } catch (\ReflectionException $e) {
                    return $e->getMessage();
                }
            }
            else{
                return false;
            }
        }
        return false;
    }

    public function verifyEmail($email):bool
    {
        $allUsers   = $this->findAll();
        $allowed    = [];
        foreach ($allUsers as $user)
        {
            $allowed[] = $user->email;
        }

        if (in_array($email,$allowed))
        {
            return true;
        }

        return false;
    }

    public function resetExpire($user_id)
    {
        $user = $this->find($user_id);
        $user->expire = strtotime('+30 minutes');
        $this->save($user);
    }

    public function resetOTPs($otp,$user_id)
    {
        $user = $this->find($user_id);
        $user->activation_code = $otp;
        $this->save($user);
    }

    public function updatePassword($password, $id)
    {
        $user = $this->find($id);
        $user->password = password_hash($password,PASSWORD_DEFAULT);
        $this->save($user);
    }

    public function updateProfileImage($id, string $newName)
    {
        $user = $this->find($id);
        $user->profile_image = $newName;
        try {
            return $this->save($user);
        } catch (\ReflectionException $e) {
            return $e->getMessage();
        }
    }

    public function resetPassword($password, $id)
    {
        $user = $this->find($id);
        $user->password = password_hash($password,PASSWORD_DEFAULT);
        try {
            $this->save($user);
            return true;
        } catch (\ReflectionException $e) {
            return $e->getMessage();
        }
    }

    public function setPlayerID($player_id,$user_id)
    {
        $user               = $this->find($user_id);
        $user->player_id    = $player_id;
        $this->save($user);
    }
}
