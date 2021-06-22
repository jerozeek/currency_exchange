<?php


namespace App\Log;


use App\Models\WithdrawalModel;

class WithdrawalLogger implements LogInterface
{

    public function log($data)
    {
        return (new WithdrawalModel())->createQueue($data);
    }
}