<?php


namespace App\Controllers\Notification;


use App\Middleware\Auth;
use App\Models\NotificationsModel;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class NotificationController extends ResourceController
{

    use ResponseTrait;

    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var NotificationsModel
     */
    private $notification;
    /**
     * @var UsersModel
     */
    private $userModel;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->notification     = new NotificationsModel();
        $this->userModel        = new UsersModel();
    }

    public function getMessages()
    {
        if ($this->auth->check())
        {
            $message = $this->notification->getMessage($this->auth->Users->id);

            if (count($message) > 0)
            {
                $output = [];

                foreach ($message as $item)
                {
                    $output[] = [
                        'message'       => $item->message,
                        'created_at'    => date('d M Y',strtotime($item->created_at))
                    ];
                }

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Message found',
                    'data'      => $output
                ]);
            }

            return $this->respond([
                'status'    => true,
                'message'   => 'No notification found'
            ],201);
        }

        return $this->failUnauthorized();
    }

    public function setPlayerId()
    {
        if ($this->auth->check())
        {
            $player_id = $this->request->getGet('player_id');
            if ($player_id != null)
            {
                $this->userModel->setPlayerID($player_id,$this->auth->Users->id);

                return $this->respond([
                    'status'    => true,
                    'message'   => 'Player ID updated successfully'
                ]);
            }

            return $this->failNotFound();
        }

        return $this->failUnauthorized();
    }

}