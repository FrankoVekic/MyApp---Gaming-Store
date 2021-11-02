<?php 

class IndexController extends Controller 
{
    public function home()
    {
        $this->view->render('home');
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

    public function shopping_cart()
    {
        $this->view->render('cart');
    }

    public function shop()
    {
        $this->view->render('shop');
    }

    public function faq()
    {
        $this->view->render('faq');
    }

    public function privacy_policy()
    {
        $this->view->render('privacy_policy');
    }

    public function checkout()
    {
        $this->view->render('checkout');
    }

    public function career()
    {
        $this->view->render('career');
    }

    public function price()
    {
        $this->view->render('price');
    }

    public function service()
    {
        $this->view->render('service');
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
}