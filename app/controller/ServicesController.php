<?php 

class ServicesController extends Controller
{
    private $viewDir = 'services' . DIRECTORY_SEPARATOR;

    public function service_list()
    {
        if(!isset($_GET['page'])){
            $page = 1;
        }
        else {
            $page=(int)$_GET['page'];
        }
        if($page===0){
            $page=1;
        }

        $serviceCount = Service::serviceCount();
        $pageCount = ceil($serviceCount/App::config('spp'));

        if($page>$pageCount){
            $page=$pageCount;
        }
        
        $this->view->render($this->viewDir . 'service_list',[
            'service'=>Service::getService($page),
            'page'=>$page,
            'pageCount'=>$pageCount,
            'random'=>Service::randomService(),
            'message'=>''
        ]);
    }

    public function service_detail()
    {
        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }

        if(!isset($_GET['id'])){
            $this->service_list();
        }
        else if(!Service::findService($_GET['id'])){
            $this->service_list();
        }
        else {
            $this->view->render($this->viewDir . 'service_detail',[
                'service'=>Service::findService($_GET['id']),
                'newservice'=>Service::latestService(),
                'random'=>Service::randomService(),
                'search'=>$search
            ]);
        }   
    }

    public function request_service()
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

        $serviceCount = Service::serviceCountSearch($search);
        $pageCount = ceil($serviceCount/App::config('spp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        if(Service::getServiceSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'service_list',[
                'service'=>Service::getServiceSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService()
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'service_list',[
                'service'=>Service::getServiceSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService()
            ]);
        }
    }
}