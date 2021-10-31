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

    public function shop()
    {
        $this->view->render('shop');
    }
}