<?php 

class NewsController Extends Controller
{
    private $viewDir = 'news' . DIRECTORY_SEPARATOR;


    public function __construct()
    {
        parent::__construct();
        $this->news = new stdClass();
        $this->news->headline ="";
        $this->news->text ="";
        $this->news->image = "";
        $this->news->author = "";
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
            'random'=>Service::randomService(),
            'sideService'=>Service::sideBarServices(),
            'sideNews'=>News::sideBarNews()
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
                'random'=>Service::randomService(),
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'index',[
                'news'=>News::findNewsSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService(),
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews()
            ]);
        }
    }

    public function publish_news(){
        $this->view->render($this->viewDir . 'publish_news',[
            'message'=>'Enter required information.'
        ]);
    }

    public function publish(){
        if(!$_POST){
            $this->publish_news();
            return;
        }

            $this->news = (object)$_POST;

            if($this->checkHeadline() && $this->checkText() && $this->checkImage()){
                News::create((array)$this->news,'noimg.png');
                $this->index();
            }
            else {
                $this->view->render($this->viewDir . 'publish_news',[
                    'message'=>'Enter required information.',
                    'news'=>$this->news
                ]);   
            }
    }

    private function checkHeadline(){
        return true;
    }

    private function checkText(){
        return true;
    }

    private function checkImage(){
        return true;
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
                'newNews'=>News::latestNews($_GET['id']),
                'random'=>Service::randomService(),
                'search'=>$search,
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
            $url = 'request_news';
        }
        return $url;
    }

    public function delete_news(){
        if(!isset($_GET['id'])){
            $this->index();
            return;
        }
        else {

            $id = $_GET['id'];
            if(News::newsExists($id) == null){
                $this->index();
                return;
            }
            else {
                News::delete($id);
                $this->index();
            }
        }
    }
}