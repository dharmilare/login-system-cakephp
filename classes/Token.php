<?php
class Token{
    public static function generate(){ //generate token
        return Session::put(config::get('session/token_name'), md5(uniqid()));
    }
    public function check($token){ //chekc if the token is equal to the current session
        $tokenName=config::get('session/token_name');
        if(Session::exists($tokenName)&& $token=== Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}