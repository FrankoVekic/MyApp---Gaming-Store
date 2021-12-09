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
            'random'=>Service::randomService()
        ]);
    }

    public function service_detail()
    {
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
                'random'=>Service::randomService()
            ]);
        }   
    }
}