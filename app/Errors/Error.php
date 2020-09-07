<?php

namespace App\Errors;


class Error
{
    public $route;   
    public $title;
    public $detail;
    public $status;

    public function __construct($route, $title = 'Page not found', $status = '404'){
        $this->route = $route;
        $this->title = $title;
        $this->detail = $title;
        $this->status = $status;  
    }
}