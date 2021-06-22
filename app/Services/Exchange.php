<?php


namespace App\Services;


class Exchange
{

    public function conversion($from,$to,$amount)
    {
        // set API Endpoint, access key, required parameters
        $endpoint   = 'convert';
        $access_key = 'b14850f91909bede5ee174463c195f92';

        // initialize CURL:
        $ch = curl_init('https://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the (still encoded) JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        // access the conversion result
        return $conversionResult['result'];
    }

}