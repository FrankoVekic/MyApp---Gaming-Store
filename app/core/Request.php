<?php 

class Request 
{
    public static function pathfinder()
    {
        $path = '/';
        if(isset($_SERVER['REDIRECTION_PATH_INFO'])){
            $path = $_SERVER['REDIRECTION_PATH_INFO'];
        }
        else if(isset($_SERVER['REQUEST_URI'])){
            $path = $_SERVER['REQUEST_URI'];
        }
        return $path;
    }

    public static function isAuthorized()
    {
        return isset($_SESSION['authorized']);
    }
}