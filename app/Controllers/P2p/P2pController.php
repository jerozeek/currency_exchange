<?php
namespace App\Controllers\P2p;

use App\Middleware\Auth;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class P2pController extends ResourceController
{

    use ResponseTrait;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct()
    {
        $this->auth     = new Auth();
    }

    public function createAddons()
    {

    }

    private function createBuyer()
    {

    }

    private function createSeller()
    {

    }

    public function loadBuyers()
    {

    }

    public function loadSellers()
    {

    }

}