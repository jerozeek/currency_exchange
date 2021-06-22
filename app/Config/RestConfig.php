<?php


namespace App\Config;


class RestConfig
{

    public $allowed_currency = [
        'ngn' => '₦',
        'usd' => '$',
        'eur' => '€',
        'gbp' => '£'
    ];

    public string $paystack_pk = 'pk_test_d236cd0f648a7429521cad6c3018cd4d920f49e0';

    public string $paystack_sk = 'sk_test_1a9869113f2ce83341eaa21b3cac176dadd689e6';

}