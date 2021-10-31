<?php 

class App {
    
    public static function start()
    {
        $path = Request::pathfinder();

        $request = explode ('/',$path);

        $class = '';
        if(!isset($request[1]) || $request[1] == ''){
            $class = 'Index';
        }
        else {
            $class = ucfirst($request[1]);
        }
        $class .='Controller';

        $method = ''; 
        if(!isset($request[2]) || $request[2] == ''){
            $method='home';
        }
        else {
            $method = $request[2];
        }

        if(class_exists($class) && method_exists($class,$method)){
            $instance = new $class();
            $instance->$method();
        }
        else {
            //error page
        }
    }

    public static function config($key)
    {
        $config = include BP_APP . 'config.php';
        return $config[$key];
    }

}