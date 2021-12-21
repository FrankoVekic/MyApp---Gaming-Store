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

    public function blog_detail()
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
        

        if(!isset($_GET['id'])){
            $this->index();
        }
        $id = $_GET['id'];
        $blogExists = Blog::blogExists($id);
        if($blogExists == null){
            $this->index();
        }
        else {
          
            $commentCount = Blog::commentCount($id);
            $pageCount = ceil($commentCount/App::config('npp'));
            
            if($page>$pageCount){
                $page=$pageCount;
            }
            if($page==0){
                $page=1;
            }

            if(isset($_SESSION['authorized'])){
                $userId = $_SESSION['authorized']->id;
                
            }
            else{
                $userId = '';
            }

            $this->view->render($this->viewDir . 'blog_detail',[
                'blog'=>Blog::blogDetail($id),
                'search'=>$search,
                'message'=>'',
                'random'=>Service::randomService(),
                'latestBlog'=>Blog::latestBlog(),
                'comment'=>Blog::findComment($id,$page),
                'page'=>$page,
                'pageCount'=>$pageCount,
                'userId'=>$userId
            ]);
        }
    }

    public function returnDetail($id)
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

        $commentCount = Blog::commentCount($id);
        $pageCount = ceil($commentCount/App::config('npp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }

        if(isset($_SESSION['authorized'])){
            $userId = $_SESSION['authorized']->id;
            
        }
        else{
            $userId = '';
        }

        $nid = $id;
        $blogExists = Blog::blogExists($nid);
        if($blogExists == null){
            $this->index();
        }
        else {
            $this->view->render($this->viewDir . 'blog_detail',[
                'blog'=>Blog::blogDetail($nid),
                'search'=>$search,
                'page'=>$pageCount,
                'pageCount'=>$pageCount,
                'message'=>'',
                'random'=>Service::randomService(),
                'latestBlog'=>Blog::latestBlog(),
                'comment'=>Blog::findComment($nid,$pageCount),
                'userId'=>$userId
            ]);
        }
    }

    public function request()
    {
        if(!empty($_POST['comment'])){
            Blog::insertComment($_POST['writer'],$_POST['comment'],$_POST['postId']);
            $this->returnDetail($_POST['postId']);
        }
    }
}