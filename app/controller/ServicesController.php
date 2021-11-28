<?php 

class ServicesController extends Controller
{
    private $viewDir = 'services' . DIRECTORY_SEPARATOR;

    public function service_list()
    {
        $this->view->render($this->viewDir . 'service_list');
    }
}