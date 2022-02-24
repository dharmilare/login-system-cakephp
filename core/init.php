<?php
session_start();

$GLOBALS['config']=array(
    'mysql'=>array(
        'host'=>"127.0.0.1",
        'username'=>'root',
        'password'=>'damilare',
        'db'=>'login_details'
    ),
    'remember'=>array(
        'cookie_name'=>'hash',
        'cookie_expiry'=> 2629743.83
    ),
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token'
    )

);
spl_autoload_register(function($class){ //load all the class in the classes folder
    require_once('classes/'. $class . '.php');
});
require_once('function/sanitize.php');

if(Cookie::exists(config::get('remember/cookie_name')) && !Session::exists(config::get('session/session_name'))){
    $hash = Cookie::get(config::get('remember/cookie_name'));
    $hashCheck= DB::getInstance()->get('users_session', array('hash', '=', $hash));//compare the hash in the db and the hash sored in the cookie

    if($hashCheck->count()){ //if the comparison is true login user
        $user= new User($hashCheck->first()->user_id);
        $user->login();

    }
}

?>