<?php

namespace App\Services;


interface StripeGateway
{

    public function startTransaction($data,$log_id);

    public function handle();

    public function paymentSuccessful($data,$status);

    public function paymentQueued();

    public function paymentFailed();

    public function cardDecline();

    public function refund();

    public function create_customer($data);

    public function retrieve($transaction_id);


}