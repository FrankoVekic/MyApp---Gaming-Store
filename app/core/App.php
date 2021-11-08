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
        
        $id= 0;
        $id = ''; 
        if(!isset($request[3]) || $request[3] == ''){
            $id=0;
        }
        else {
            $id=$request[3];
        }

        if(class_exists($class) && method_exists($class,$method)){
            $instance = new $class();
            if($id==0){
                $instance->$method();
            }else {
                $instance->$method($id);
            }
        }
        else {
            $view = new View();
            $view->render('error');
        }
    }

    public static function config($key)
    {
        $config = include BP_APP . 'config.php';
        return $config[$key];
    }

}