<?php

namespace App\Errors;


class Errors 
{    
    private static $errors = [];

    public static function setError(Error $error){
        self::$errors[] = $error;
    }
   
    public static function getErrors(){
        return collect(self::$errors);
    }

}