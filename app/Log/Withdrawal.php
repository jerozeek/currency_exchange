<?php


namespace App\Log;


use App\Models\WithdrawalModel;

class Withdrawal implements LogInterface
{

    public function log($data)
    {
        return (new WithdrawalModel())->create($data);
    }
}