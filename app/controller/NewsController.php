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
        $pageCount = ceil($newsCount/App::config('spp'));
        
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
            'message'=>''
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
        $pageCount = ceil($newsCount/App::config('spp'));
        
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
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'index',[
                'news'=>News::findNewsSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\''
            ]);
        }
    }
}