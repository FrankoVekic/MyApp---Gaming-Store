<?php 

class IndexController extends Controller 
{

    public function home()
    {
        $this->view->render('home',[
            'equipment'=>Equipment::newEquipment(),
            'news'=>News::latestNewsHome()
        ]);
    }

    public function about()
    {
        $this->view->render('about');
    }

    public function blog()
    {
        $this->view->render('blog');
    }

    public function contact()
    {
        $this->view->render('contact');
    }

    public function faq()
    {
        $this->view->render('faq');
    }

    public function privacy_policy()
    {
        $this->view->render('privacy_policy');
    }
    
    public function price()
    {
        $this->view->render('price');
    }

    public function service()
    {
        $this->view->render('service');
    }

    public function service_detail()
    {
        $this->view->render('service_detail');
    }
    public function login()
    {
        $this->view->render('login',[
            'message'=>'Enter required information.'
        ]);
    }

    public function logout()
    {
        unset($_SESSION['authorized']);
        session_destroy();
        $this->home();
    }
    
    public function error()
    {
        $this->view->render('error');
    }

    public function authorization()
    {
        if(!isset($_POST['email']) || !isset($_POST['password'])){
            $this->login();
            return;
        }
        if(strlen(trim($_POST['email'])) ===0){
            $this->loginView('','Email is required');
            return;
        }
        if(strlen(trim($_POST['password'])) ===0){
            $this->loginView($_POST['email'],'Password is required');
            return;
        }
        $user = Operator::authorize($_POST['email'],$_POST['password']);
        if($user == null){
            $this->loginView($_POST['email'],'Incorrect data given, try again.');
            return;
        }

        $_SESSION['authorized']=$user;
        $view = new View();
        $view->render('home');
    }
    
    private function loginView($email,$message)
    { 
        $this->view->render('login',
        ['email'=>$email,
         'message'=>$message]);
    }

    private function registerView($name,$surname,$email,$message)
    { 
        $this->view->render('register',
        ['email'=>$email,
        'name'=>$name,
        'surname'=>$surname,
        'message'=>$message]);
    }

    public function register()
    {
        $this->view->render('register',[
            'message'=>'Enter required information.'
        ]);
    }

    public function registration()
    {
        $uppercase = preg_match('@[A-Z]@', $_POST['password']);
        $number = preg_match('@[0-9]@', $_POST['password']);

        if(strlen(trim($_POST['name'])) ===0){
            $this->registerView('',$_POST['surname'],$_POST['email'],'Name empty!');
            return;
        }
        if(strlen(trim($_POST['surname'])) ===0){
            $this->registerView($_POST['name'],'',$_POST['email'],'Surname empty!');
            return;
        }
        if(strlen(trim($_POST['email'])) ===0){
            $this->registerView($_POST['name'],$_POST['surname'],'','Email empty!');
            return;
        }
        if(strlen(trim($_POST['password'])) ===0){
            $this->registerView($_POST['name'],$_POST['surname'],$_POST['email'],'Password is required');
            return;
        }
        if(!$uppercase || !$number || strlen($_POST['password']) < 6){
            $this->registerView($_POST['name'],$_POST['surname'],$_POST['email'],'Password  must contain at least one capital letter, one number and at least 6 characters');
            return;
        }
        if($_POST['password'] != $_POST['passwordrepeat']){
            $this->registerView($_POST['name'],$_POST['surname'],$_POST['email'],'Passwords must match.');
            return;
        }

    $operater = Operator::registration($_POST['name'],$_POST['surname'],$_POST['email'],$_POST['password'],$_POST['passwordrepeat']);
    if($operater == false){
        $this->registerView('','','','Incorrect input, try again.');
        return;
    }
    if($operater == true){
        $this->loginView($_POST['email'],'Registered successfully.');
        return;
    } 
}
}