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

    public function games()
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

        $gameCount = Equipment::equipmentCount();
        $pageCount = ceil($gameCount/App::config('epp'));

        if($page>$pageCount){
            $page=$pageCount;
        }
        
        $this->view->render($this->viewDir . 'games',[
            'games'=>Games::findGames($page),
            'page'=>$page,
            'pageCount'=>$pageCount
        ]);
    }

    public function game_detail()
    {
        if(!isset($_GET['id'])){
            $this->games();
        }

        $id = $_GET['id'];
        $gameExists = Games::gameExists($id);
        if($gameExists == null){
            $this->games();
        }
        else {
            $this->view->render($this->viewDir . 'game_detail',[
                'game'=>Games::gameDetail($id),
                'newGames'=>Games::latestGames()
            ]);
        }
    }

    public function equipment_detail()
    {
        if(!isset($_GET['id'])){
            $this->equipment();
        }
        $id = $_GET['id'];
        $equipmentExists = Equipment::equipmentExists($id);
        if($equipmentExists == null){
            $this->equipment();
        }
        else {
            $this->view->render($this->viewDir . 'equipment_detail',[
                'equipment'=>Equipment::equipmentDetail($id),
                'newEquipment'=>Equipment::latestEquipment()
            ]);
        }
    }

    public function shopping_cart()
        {
            {
            
                if(isset($_POST['id'])){
        
                    $game = Games::gameExists($_POST['id']);
                    
                    if(isset($_SESSION['cart'])){
                
                    $game_id = array_column($_SESSION['cart'],'id');
                
                        if(!in_array($_POST['id'],$game_id)){
                            $count = count($_SESSION['cart']);
        
                            $_SESSION['cart'][$count] = [
                                'id'=>$_POST['id'],
                                'name'=>$game->name,
                                'price'=>$game->price,
                                'description'=>$game->description,
                                'quantity'=> $_POST['quantity'],
                                'maxquan'=>$game->quantity,
                                'console'=>$game->console,
                                'image'=>$game->image
                            ];
                        $this->view->render($this->viewDir . 'cart',[
                            'message'=>'Your Shopping Cart'
                        ]);
                    }else {
                        for($i=0;$i<count($game_id); $i++){
                            if($game_id[$i] == $_POST['id']){
                                $_SESSION['cart'][$i]['quantity'] += $_POST['quantity'];
                            }
                            $this->view->render($this->viewDir . 'cart',[
                                'message'=>'Your Shopping Cart'
                            ]);
                        }
                    }
                }
                else {
                    $game_array = [
                        'id'=>$_POST['id'],
                        'name'=>$game->name,
                        'price'=>$game->price,
                        'description'=>$game->description,
                        'quantity'=> $_POST['quantity'],
                        'maxquan'=>$game->quantity,
                        'console'=>$game->console,
                        'image'=>$game->image
                    ];
                    $_SESSION['cart'][0] = $game_array;
        
                    $this->view->render($this->viewDir . 'cart');
                }
            }
            else {
                $this->view->render($this->viewDir . 'cart');
            }
        }
    }
}