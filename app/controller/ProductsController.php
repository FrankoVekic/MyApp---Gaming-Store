<?php 

class ProductsController  extends Controller
{

    private $viewDir = 'products' . DIRECTORY_SEPARATOR;

    public function equipment()
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
        
        $equipmentCount = Equipment::equipmentCount();
        $pageCount = ceil($equipmentCount/App::config('epp'));

        if($page>$pageCount){
            $page=$pageCount;
        }

        $this->view->render($this->viewDir . 'equipment',[
            'equipment'=>Equipment::readEquipment($page),
            'page'=>$page,
            'pageCount'=>$pageCount
        ]);
    }
}