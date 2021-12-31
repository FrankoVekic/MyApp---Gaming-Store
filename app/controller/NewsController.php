<?php 

class NewsController Extends Controller
{
    private $viewDir = 'news' . DIRECTORY_SEPARATOR;


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

        $newsCount = News::newsCountSearch($search);
        $pageCount = ceil($newsCount/App::config('npp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        
        $this->view->render($this->viewDir . 'index',[
            'news'=>News::findNews($page),
            'page'=>$page,
            'search'=>$search,
            'pageCount'=>$pageCount,
            'message'=>'',
            'random'=>Service::randomService()
        ]);
    }

    public function request_news()
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

        $newsCount = News::newsCountSearch($search);
        $pageCount = ceil($newsCount/App::config('npp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        if(News::findNewsSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'index',[
                'news'=>News::findNewsSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService()
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'index',[
                'news'=>News::findNewsSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService()
            ]);
        }
    }

    public function news_detail()
    {

        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }

        if(!isset($_GET['id'])){
            $this->index();
        }
        $id = $_GET['id'];
        $newsExists = News::newsExists($id);
        if($newsExists == null){
            $this->index();
        }
        else {
            $this->view->render($this->viewDir . 'news_detail',[
                'news'=>News::newsDetail($id),
                'newNews'=>News::latestNews(),
                'random'=>Service::randomService(),
                'search'=>$search
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
            $url = 'request_news';
        }
        return $url;
    }
}