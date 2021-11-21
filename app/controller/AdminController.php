<?php 

class AdminController extends Controller 
{

    public function __construct()
    {
        parent::__construct();
        if(!isset($_SESSION['authorized'])){
            $this->view->render('login',[
                'message'=>'You have to be logged in.'
            ]);
            exit();
        }
        else if(isset($_SESSION['authorized']) && $_SESSION['authorized']->role != 'admin'){
            $this->view->render('home');
            exit();
        }
    }
}