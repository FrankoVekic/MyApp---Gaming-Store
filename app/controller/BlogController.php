<?php 

class BlogController extends Controller 
{

    private $viewDir = 'blogs' . DIRECTORY_SEPARATOR;

    public function index ()
    {
        if(!isset($_GET['page'])){
            $page=1;
        }
        else {
            $page=(int)$_GET['page'];
        }
        
        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }

        $blogCount = Blog::blogCountSearch($search);
        $pageCount = ceil($blogCount/App::config('npp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        
        $this->view->render($this->viewDir . 'index',[
            'blog'=>Blog::findBlog($page),
            'page'=>$page,
            'search'=>$search,
            'pageCount'=>$pageCount,
            'message'=>'',
            'random'=>Service::randomService(),
            'latestBlog'=>Blog::latestBlog()
        ]);
    }
}