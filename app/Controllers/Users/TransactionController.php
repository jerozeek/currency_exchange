<?php


namespace App\Controllers\Users;


use App\Middleware\Auth;
use App\Models\ExchangeModel;
use App\Models\TransactionsModel;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class TransactionController extends ResourceController
{

    use ResponseTrait;

    private Auth $auth;
    private UsersModel $userModel;
    private TransactionsModel $transaction;
    private ExchangeModel $exchangeModel;

    public function __construct()
    {
        $this->auth             = new Auth();
        $this->userModel        = new UsersModel();
        $this->transaction      = new TransactionsModel();
        $this->exchangeModel    = new ExchangeModel();
    }

    public function transactions()
    {
        if ($this->auth->check())
        {
            $transact = $this->transaction->fetchTransactions($this->auth->Users->id);

            if (count($transact) > 0)
            {
                return $this->respond([
                    'status'    => true,
                    'message'   => 'Transaction found',
                    'data'      => $transact
                ]);
            }

            return $this->respond([
                'status'    => true,
                'message'   => 'No record found',
                'data'      => null
            ],201);
        }

        return $this->failUnauthorized();
    }

    public function exchanges()
    {
        if ($this->auth->check())
        {
            $list = $this->exchangeModel->getExchanges($this->auth->Users->id);
            if (count($list) > 0)
            {
                return $this->respond([
                    'status'    => true,
                    'message'   => 'Exchange found',
                    'data'      => $list
                ]);
            }

            return $this->respond([
                'status'    => true,
                'message'   => 'No record found',
                'data'      => null
            ],201);
        }

        return $this->failUnauthorized();
    }


}