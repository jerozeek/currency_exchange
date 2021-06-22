<?php

namespace App\ThirdParty;

class Token
{
    public static function generate(){
       return Session::set(REFRESH_TOKEN,md5(uniqid()));
    }
    public static function check($token):bool
    {
        //check if a user token exist
        $tokenName = REFRESH_TOKEN;
        if (Session::exist($tokenName) && $token === Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }


    public static function create_token()
    {
        return Session::set(TOKEN,md5(uniqid()));
    }

    public static function check_next($token):bool
    {
        //check if a user token exist
        $tokenName = TOKEN;
        if (Session::exist($tokenName) && $token === Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}