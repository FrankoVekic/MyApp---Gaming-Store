<?php 

class ServicesController extends Controller
{
    private $viewDir = 'services' . DIRECTORY_SEPARATOR;

    public function service_list()
    {
        $this->view->render($this->viewDir . 'service_list',[
            'service'=>Service::getService()
        ]);
    }

    public function service_detail()
    {
        if(!isset($_GET['id'])){
            $this->service_list();
        }
        else {
            $this->view->render($this->viewDir . 'service_detail',[
                'service'=>Service::findService($_GET['id']),
                'newservice'=>Service::latestService()
            ]);
        }   
    }
}