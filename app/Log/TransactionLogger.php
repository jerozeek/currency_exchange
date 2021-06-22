<?php


namespace App\Log;


use App\Models\TransactionsModel;

class TransactionLogger implements LogInterface
{

    /**
     * @var TransactionsModel
     */
    private TransactionsModel $transaction;

    function __construct(){
        $this->transaction = new TransactionsModel();
    }

    public function log($data)
    {
        return $this->transaction->create($data);
    }
}