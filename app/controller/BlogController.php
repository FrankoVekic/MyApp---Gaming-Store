<?php 

class BlogController extends Controller 
{

    
    private $viewDir = 'blogs' . DIRECTORY_SEPARATOR;
    private $imgDir = BP . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR;
    private $blog;
    private $message;

    public function __construct()
    {
        parent::__construct();
        $this->blog = new stdClass();
        $this->blog->title="";
        $this->blog->text="";
        $this->blog->image="";
    }

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
        $pageCount = ceil($blogCount/App::config('bpp'));
        
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
            'latestBlog'=>Blog::latestBlog(),
            'sideService'=>Service::sideBarServices(),
            'sideNews'=>News::sideBarNews()
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
                'userId'=>$userId,
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
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
                'userId'=>$userId,
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
    }

    public function request()
    {
        if(!isset($_POST['postId'])){
            $this->blog_detail();
        }

        if(!empty($_POST['comment'])){
            Blog::insertComment($_POST['writer'],$_POST['comment'],$_POST['postId']);
            $this->returnDetail($_POST['postId']);
        }
        else {
            $this->returnDetail($_POST['postId']);
        }
        $this->blog_detail();
    }

    public function request_blog()
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
        $pageCount = ceil($blogCount/App::config('bpp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        if(Blog::findBlogSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'index',[
                'blog'=>Blog::findBlogSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService(),
                'latestBlog'=>Blog::latestBlog(),
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'index',[
                'blog'=>Blog::findBlogSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService(),
                'latestBlog'=>Blog::latestBlog(),
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
    }

    public static function checkSearch()
    {
        $url = '';
        if(!isset($_GET['search']) || strlen(trim($_GET['search'])) == 0){
            $url = 'index';
        }
        else 
        {
            $url = 'request_blog';
        }
        return $url;
    }

    public function delete_comment()
    {
        if(!isset($_SESSION['authorized'])){
            $this->index();
            return;
        }

        $user = $_SESSION['authorized']->id;

        if(!isset($_GET['blog'])){
            $this->index();
            return;
        }

        if(!isset($_GET['comment'])){
            $this->returnDetail($_GET['blog']);
            return;
        }
        
        if(Comment::commentExists($_GET['comment']) == null){
            $this->returnDetail($_GET['blog']);
            return;
        }
        if(Blog::checkBeforeDelete($_GET['comment'],$_GET['blog'],$user) == null){
            $this->returnDetail($_GET['blog']);
            return;
        }

        Comment::delete($_GET['comment']);
        $this->detailPath($_GET['blog']);
    }

    public function pageById($id)
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
                'userId'=>$userId,
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
    }

    public function detailPath($id){
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
        

        if(!isset($_GET['blog'])){
            $this->index();
        }
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
                'userId'=>$userId,
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
    }

    public function update_comment()
    {
        if(!isset($_SESSION['authorized'])){
            $this->index();
            return;
        }
        $user = $_SESSION['authorized']->id;

        if(!isset($_GET['blog'])){
            $this->index();
            return;
        }
        if(Blog::blogExists($_GET['blog']) == null){
            $this->index();
            return;
        }

        if(!isset($_GET['comment'])){
            $this->detailPath($_GET['blog']);
            return;
        }

        if(Comment::commentExists($_GET['comment']) == null){
            $this->detailPath($_GET['blog']);
            return;
        }

        if(Blog::checkBeforeDelete($_GET['comment'],$_GET['blog'],$user) == null){
            $this->detailPath($_GET['blog']);
            return;
        }
        
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

        $id = $_GET['blog'];
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

        $this->view->render($this->viewDir . 'blog_edit',[
            'blog'=>Blog::blogDetail($id),
            'search'=>$search,
            'message'=>'',
            'random'=>Service::randomService(),
            'latestBlog'=>Blog::latestBlog(),
            'comment'=>Blog::getCommentEdit($_GET['comment'],$_GET['blog'],$page),
            'editComment'=>Blog::getComment($_GET['comment']),
            'page'=>$page,
            'pageCount'=>$pageCount,
            'userId'=>$userId,
            'sideService'=>Service::sideBarServices(),
            'sideNews'=>News::sideBarNews()
        ]);
    }
}

    public function new_blog()
    {
        if(!isset($_SESSION['authorized'])){
            $this->index();
        }

        $this->view->render($this->viewDir . 'new_blog',[
            'blog'=>$this->blog,
            'message'=>'Fill in the required fields.'
        ]);
    }

    public function upload_blog()
    {
        if(!isset($_SESSION['authorized'])){
            $this->index();
        }

        if(!$_POST){
            $this->new_blog();
            return;
        }

        $this->blog=(object)$_POST;

        if($this->verify_blog_title() && 
           $this->verify_blog_text()   
        ){
            $file = $_FILES['image']['name'];

            if (!$file){
                $img = $_SESSION['image_blog'];
                if($img == ''){
                    $img = null;
                }
                Blog::create((array)($this->blog),$img);
                unset($_SESSION['image_blog']);
            }else {
                $img = $this->blog->title . $this->blog->author . '.jpg';
                Blog::create((array)($this->blog),$img);
        }
            if(isset($_FILES['image'])){
                move_uploaded_file($_FILES['image']['tmp_name'],
                $this->imgDir . $this->blog->title . $this->blog->author . '.jpg');
            }
            $this->index();
        }
        else {
            $this->view->render($this->viewDir . 'new_blog',[
                'blog'=>$this->blog,
                'message'=>$this->message
            ]);
        }
    }

    private function verify_blog_title()
    {
        if(!isset($this->blog->title)){
            $this->message = "Title is required";
            return false;
        }
        if(strlen(trim($this->blog->title)) === 0){
            $this->message="Title is required.";
            return false;
        }
        if(strlen(trim($this->blog->title))<3){
            $this->message="Title is too short (atleast 3 letters).";
            return false;
        }
        if(strlen(trim($this->blog->title))>50){
            $this->message="Max number of letters is 50.";
            return false;
        }
        return true;
    }

    private function verify_blog_text()
    {
        if(!isset($this->blog->text)){
            $this->message = "Blog text is required.";
            return false;
        }
        if(empty($this->blog->text)){
            $this->message = "Blog text is required.";
            return false;
        }
        if(strlen(trim($this->blog->text)) === 0){
            $this->message="Blog text is required.";
            return false;
        }
        if(strlen(trim($this->blog->text))<10){
            $this->message="Blog text is too short.";
            return false;
        }
        return true;
    }


    public function update_blog()
    {
        if(!isset($_GET['blog'])){
            $this->index();
            return;
        }

        if(Blog::blogExists($_GET['blog']) == null){
            $this->index();
            return;
        }

        if(Blog::myBlog($_SESSION['authorized']->id,$_GET['blog']) == null){
            $this->index();
            return;
        }

        $blog = Blog::blogDetail($_GET['blog']);
        $this->view->render($this->viewDir . 'update_blog',[
            'blog'=>$blog,
            'message'=> 'Change data in your blog.'
        ]);
    }

    public function change_blog_data()
    {
        if(!isset($_SESSION['authorized'])){
            $this->index();
            return;
        }

        if(!$_POST){
            $this->index();
            return;
        }

        $this->blog = (object)$_POST;

        if($this->verify_blog_title() 
        && $this->verify_blog_text()){

            $file = $_FILES['image']['name'];
            if (!$file){
                $img = $_SESSION['image_blog'];
                Blog::update((array)($this->blog),$img);
                unset($_SESSION['image_blog']);
            }else {
                $img = $this->blog->id . 'a.jpg';
                Blog::update((array)($this->blog),$img);
        }
            if(isset($_FILES['image'])){
                move_uploaded_file($_FILES['image']['tmp_name'],
                $this->imgDir . $this->blog->id . 'a.jpg');
            }
            $this->pageById($this->blog->id);
        }
        else {
            $this->view->render($this->viewDir . 'update_blog',[
                'blog'=>$this->blog,
                'message'=>$this->message
            ]);
        }
    }

    public function delete_blog()
    {
        if(!isset($_GET['blog'])){
            $this->index();
            return;
        }
        if(Blog::blogExists($_GET['blog']) == null){
            $this->index();
            return;
        }

        Blog::delete($_GET['blog']);
        $this->index();
    }
}