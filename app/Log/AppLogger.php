<?php
namespace App\Log;

class AppLogger
{

    public function log($data, LogInterface $logger = null){
        //set a specific logger if not specify
        $logger = $logger ? : new TransactionLogger();

        return $logger->log($data);
    }

}