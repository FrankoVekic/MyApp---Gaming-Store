<?php 

class AuthorizeController extends Controller
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
    }
}