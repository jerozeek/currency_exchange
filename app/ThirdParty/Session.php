<?php
namespace App\ThirdParty;

class Session
{
    public static function set($name,$value){
        return $_SESSION[$name] = $value;
    }

    public static function exist($name){
        return (isset($_SESSION[$name]) ? true : false);
    }

    public static function get($name){
        return $_SESSION[$name];
    }

    public static function delete($name){
        if (self::exist($name)){
            unset($_SESSION[$name]);
        }
    }

    public static function flash($name, $string = ''){
        if (self::exist($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
        }else{
            self::set($name,$string);
        }
    }

    public static function clear(){
        session_destroy();
        session_unset();
    }


}