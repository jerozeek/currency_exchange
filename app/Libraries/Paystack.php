<?php
namespace App\Libraries;

use App\Config\RestConfig;


class Paystack {

    protected $secret_key;
    protected $public_key;

    protected $config;

    public function __construct() {
        $this->config = new RestConfig();
        $this->secret_key = $this->config->paystack_sk;
        $this->public_key = $this->config->paystack_pk;
    }

    private function curl($url, $use_post, $post_data=[])
    {
        $curl = curl_init();
        $headers = [
            "Authorization: Bearer {$this->secret_key}",
            'Content-Type: application/json'
        ];
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        if($use_post){
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
        }
        //Modify this two lines to suit your needs
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function init($ref, $amount_in_kobo, $email, $metadata_arr=[], $callback_url="", $return_obj=false){        
        if($ref && $amount_in_kobo && $email){
            $url = "https://api.paystack.co/transaction/initialize/";
            $post_data = [
                'reference'=>$ref,
                'amount'=>$amount_in_kobo,
                'email'=>$email,
                'metadata'=>json_encode($metadata_arr),
                'callback_url'=>$callback_url
            ];
            //curl($url, $use_post, $post_data=[])
            $response = $this->curl($url, TRUE, $post_data);
            if($response){                
                //return the whole Object if $return_obj is true, otherwise return just the authorization_url
                return $return_obj ? json_decode($response) : json_decode($response)->data->authorization_url;
            }
            
            //api request failed
            return FALSE;
        }
        
        return FALSE;
    }

    public function initSubscription($amount_in_kobo, $email, $plan, $metadata_arr=[], $callback_url="", $return_obj=false){        
        if($amount_in_kobo && $email && $plan){
            //https://api.paystack.co/transaction/initialize
            $url = "https://api.paystack.co/transaction/initialize/";
                
            $post_data = [
                'amount'=>$amount_in_kobo,
                'email'=>$email,
                'plan'=>$plan,
                'metadata'=>json_encode($metadata_arr),
                'callback_url'=>$callback_url
            ];

            //curl($url, $use_post, $post_data=[])
            $response = $this->curl($url, TRUE, $post_data);
            
            if($response){                
                //return the whole decoded object if $return_obj is true, otherwise return just the authorization_url
                return $return_obj ? json_decode($response) : json_decode($response);
            }
            //api request failed
            return FALSE;
        }
        
        return FALSE;
    }	
	

    public function verifyTransaction($transaction_reference){
        //https://api.paystack.co/transaction/verify/:reference
        $url = "https://api.paystack.co/transaction/verify/".$transaction_reference;
        
        if ($url){
            return json_decode($this->curl($url, FALSE));
        }
        return false;

    }

    public function chargeReturningCustomer($auth_code, $amount_in_kobo, $email, $ref="", $metadata_arr=[]){
        
        if($auth_code && $amount_in_kobo && $email){
            //https://api.paystack.co/transaction/charge_authorization
            $url = "https://api.paystack.co/transaction/charge_authorization/";
                
            $post_data = [
                'authorization_code'=>$auth_code,
                'amount'=>$amount_in_kobo,
                'email'=>$email,
                'reference'=>$ref,
                'metadata'=>json_encode($metadata_arr)
            ];

            //curl($url, $use_post, $post_data=[])
            $response = $this->curl($url, TRUE, $post_data);
            
            if($response){                
                //return the whole json decoded object 
                return json_decode($response);
            }
            
            //api request failed
            return FALSE;
        }
        
        //required fields are not set
        return FALSE;
    }

    public function createCustomer($email, $first_name='', $last_name='', $phone='', $meta=[]){
        //https://api.paystack.co/customer
        $url = "https://api.paystack.co/customer";
        
        if($email && $url){
            $post_data = [
                'email'=>$email,
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'phone'=>$phone,
                'metadata'=>json_encode($meta)
            ];
            
            //curl($url, $use_post, $post_data=[])
            $response = $this->curl($url, TRUE, $post_data);
            
            //decode the response
            $data = json_decode($response);
            
            if($data && $data->status){                
                //return customer_code and ID
                return ['customer_id'=>$data->data->id, 'customer_code'=>$data->data->customer_code];
            }
            
            //api request failed
            return FALSE;
        }
        
        //required fields are not set
        return FALSE;
    }

    function process_withdrawal($amount,$recipient)
    {
        $bearer_key = (new RestConfig())->paystack_sk;
        $amount = $amount.'00';
        $result = array();
        $postdata = array('source' => 'balance', 'reason' => "funds", 'amount' => $amount, "recipient" => "$recipient");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transfer");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-type: application/json';
        $headers[] = "Authorization: Bearer $bearer_key";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $request = curl_exec($ch);
        curl_close($ch);
        return $request;
    }

    function verify_account($act_number,$bank){
        $bearer_key = (new RestConfig())->paystack_sk;
        $sh = curl_init();
        $headr = array();
        $headr[] = 'Content-length: 0';
        $headr[] = 'Content-type: application/json';
        $headr[] = "Authorization: Bearer $bearer_key";
        // pass header variable in curl method
        curl_setopt($sh, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($sh,CURLOPT_URL,"https://api.paystack.co/bank/resolve?account_number=$act_number&bank_code=$bank");
        curl_setopt($sh,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($sh,CURLOPT_HEADER,0);
        $output = curl_exec($sh);
        return $output;
        curl_close($sh);
    }

    function initiate_recipient($holder_name,$description,$acct_number,$bank_code){
        $bearer_key = (new RestConfig())->paystack_sk;
        $result = array();
        $postdata =  array( 'type'=>'nuban','name'=>$holder_name,'description'=>$description,'account_number'=>"$acct_number",'bank_code'=>$bank_code,'currency'=>'NGN');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.paystack.co/transferrecipient");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-type: application/json';
        $headers[] = "Authorization: Bearer $bearer_key";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $request = curl_exec ($ch);
        curl_close ($ch);
        return $request;
    }

    public function supportedBank()
    {
        $bearer_key = (new RestConfig())->paystack_sk;
        $sh = curl_init();
        $headr = array();
        $headr[] = 'Content-length: 0';
        $headr[] = 'Content-type: application/json';
        $headr[] = "Authorization: Bearer $bearer_key";
        // pass header variable in curl method
        curl_setopt($sh, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($sh,CURLOPT_URL,"https://api.paystack.co/bank");
        curl_setopt($sh,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($sh,CURLOPT_HEADER,0);
        $output = curl_exec($sh);
        return json_decode($output);
        curl_close($sh);

    }
}
