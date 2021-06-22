<?php

namespace App\Models;

use App\Entities\NotificationEntity;
use App\Events\OneSignal;
use CodeIgniter\Model;

class NotificationsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'notifications';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = NotificationEntity::class;
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
	    'user_id','player_id','message','status','created_at','updated_at','deleted_at'
    ];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
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

	public function create(array $data)
    {
        return $this->insert($data);
    }

    public function getMessage($user_id):array
    {
        return $this->where(['user_id' => $user_id])->get()->getResult();
    }

    public function setMessage($player_id = null, int $user_id = 0, string $message = null)
    {
        if (!is_null($player_id))
        {
            $this->sendPushNotification($player_id,$message);
        }

        if ($user_id > 0)
        {
            $this->create([
                'user_id'       => $user_id,
                'player_id'     => $player_id,
                'message'       => $message,
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function sendPushNotification($player_id,$message)
    {
        $oneSignal = new OneSignal();
        $oneSignal->player_id   = $player_id;
        $oneSignal->content     = ["en" => $message];
        $oneSignal->data        = ["Skybloom" => "Sending Push Notification from direct Api"];
        $oneSignal->send();
    }

    //center area yawuza
}
