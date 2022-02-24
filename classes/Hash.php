<?php
class Hash{
    public static function make($string /* ,$salt="" */){ //for hashing
        return hash('sha256', $string /* ,$salt */);
    }


    public static function salt($length){ // create a salt
        return bin2hex(random_bytes($length));
    }

    public static function unique(){ //create a unique number for rememembering a user
        return self::make(uniqid());
    }
}