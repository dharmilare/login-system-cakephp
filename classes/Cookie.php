<?php
class Cookie{
    public static function exists($name){ //to check if cookie is set
        return (isset($_COOKIE[$name])) ? true : false;
    }
    public static function get($name){ //to get cookie name
        return $_COOKIE[$name];
    }
    public static function put($name, $value, $expiry){ // to store a cookie
        if(setcookie($name, $value, time()+ $expiry, '/')){
            return true;
        }
        return false;
    }
    public static function delete($name){ //to delete a cookie
        self::put($name, '', time()-1);
    }
}