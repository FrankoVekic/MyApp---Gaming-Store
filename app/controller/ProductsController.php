<?php 

class ProductsController extends Controller
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
        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }
        
        $equipmentCount = Equipment::equipmentCount();
        $pageCount = ceil($equipmentCount/App::config('epp'));

        if($page>$pageCount){
            $page=$pageCount;
        }

        $this->view->render($this->viewDir . 'equipment',[
            'equipment'=>Equipment::readEquipment($page),
            'page'=>$page,
            'pageCount'=>$pageCount,
            'search'=>$search,
            'message'=>''
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

        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }

        $gameCount = Games::gameCount();
        $pageCount = ceil($gameCount/App::config('epp'));

        if($page>$pageCount){
            $page=$pageCount;
        }
        
        $this->view->render($this->viewDir . 'games',[
            'games'=>Games::findGames($page),
            'page'=>$page,
            'pageCount'=>$pageCount,
            'search'=>$search,
            'message'=>''
        ]);
    }

    public function request_game()
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

        $gameCount = Games::gameCountSearch($search);
        $pageCount = ceil($gameCount/App::config('epp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        if(Games::findGamesSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGamesSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGamesSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\''
            ]);
        }
    }

    public function request_equipment()
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

        $gameCount = Equipment::equipmentCountSearch($search);
        $pageCount = ceil($gameCount/App::config('epp'));
        
        if($page>$pageCount){
            $page=$pageCount;
        }
        if($page==0){
            $page=1;
        }
        if(Games::findGamesSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::findEquipmentSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::findEquipmentSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\''
            ]);
        }
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
                if(isset($_POST['id'])){
                    if(isset($_GET['game'])){
                        $product = Games::gameExists($_POST['id']);
                    
                    if(isset($_SESSION['cart'])){
                
                    $game_name = array_column($_SESSION['cart'],'name');
                
                        if(!in_array($product->name,$game_name)){
                            $count = count($_SESSION['cart']);
        
                            $_SESSION['cart'][$count] = [
                                'name'=>$product->name,
                                'price'=>$product->price,
                                'description'=>$product->description,
                                'quantity'=> $_POST['quantity'],
                                'maxquan'=>$product->quantity,
                                'console'=>$product->console,
                                'image'=>$product->image
                            ];
                        $this->view->render($this->viewDir . 'cart',[
                            'total'=>$this->total()
                        ]);
                    }else {
                        for($i=0;$i<count($game_name); $i++){
                            if($game_name[$i] == $product->name){
                                $_SESSION['cart'][$i]['quantity'] += $_POST['quantity'];
                            }
                            $this->view->render($this->viewDir . 'cart',[
                                'total'=>$this->total()
                            ]);
                        }
                    }
                }
                else {
                    $game_array = [
                        'name'=>$product->name,
                        'price'=>$product->price,
                        'description'=>$product->description,
                        'quantity'=> $_POST['quantity'],
                        'maxquan'=>$product->quantity,
                        'console'=>$product->console,
                        'image'=>$product->image
                    ];
                    $_SESSION['cart'][0] = $game_array;
        
                    $this->view->render($this->viewDir . 'cart',[
                        'total'=>$this->total()
                    ]);
                }
            }
                    else if(isset($_GET['equipment'])){
                        $product = Equipment::equipmentExists($_POST['id']);
                    
                    if(isset($_SESSION['cart'])){
                
                    $equipment_name = array_column($_SESSION['cart'],'name');
                
                        if(!in_array($product->name,$equipment_name)){
                            $count = count($_SESSION['cart']);
        
                            $_SESSION['cart'][$count] = [
                                'id'=>$_POST['id'],
                                'name'=>$product->name,
                                'price'=>$product->price,
                                'description'=>$product->description,
                                'quantity'=> $_POST['quantity'],
                                'maxquan'=>$product->quantity,
                                'image'=>$product->image
                            ];
                        $this->view->render($this->viewDir . 'cart',[
                            'total'=>$this->total()
                        ]);
                    }else {
                        for($i=0;$i<count($equipment_name); $i++){
                            if($equipment_name[$i] == $product->name){
                                $_SESSION['cart'][$i]['quantity'] += $_POST['quantity'];
                            }
                            $this->view->render($this->viewDir . 'cart',[
                                'total'=>$this->total()
                            ]);
                        }
                    }
                }
                else {
                    $game_array = [
                        'name'=>$product->name,
                        'price'=>$product->price,
                        'description'=>$product->description,
                        'quantity'=> $_POST['quantity'],
                        'maxquan'=>$product->quantity,
                        'image'=>$product->image
                    ];
                    $_SESSION['cart'][0] = $game_array;
        
                    $this->view->render($this->viewDir . 'cart',[
                        'total'=>$this->total()
                    ]);
                }

                    }
                    
            }else if(isset($_GET['remove']))
            {
                foreach($_SESSION['cart'] as $product=>$value){
                    if($value['name'] == $_GET['remove']){
                        unset($_SESSION['cart'][$product]);
                        $this->view->render($this->viewDir . 'cart',[
                            'total'=>$this->total()
                        ]);
                    } 
                 }
            }
            else {
                $this->view->render($this->viewDir . 'cart',[
                    'total'=> $this->total()
                ]);
        }
    }
    
    public static function shopCount()
    {
       $count = 0;
       if(empty($_SESSION['cart']) || !isset($_SESSION['cart']))
       {
         $count = 0;
         return;
    }
       else { 
           $count = count($_SESSION['cart']);
           echo '(' . $count .')';
       } 
    }

    public static function total ()
    {
    if(isset($_SESSION['cart'])){
      $total = 0;
      foreach($_SESSION['cart'] as $product=>$value){
      $total = $total + $value['quantity'] * $value['price']; 
    }
    }
    else {
        $total = 0;
        return $total;
}
      return $total;
    }

    public function checkout()
    {
        if(empty($_SESSION['cart'])){
            $this->view->render($this->viewDir . 'cart',[
                'message'=>"Enter coupon code.",
                'total'=>$this->total()
            ]);
        }
        else {
        if(isset($_SESSION['couponcode'])){
            $this->view->render($this->viewDir . 'checkout',[
                'total'=>$this->total() - ($this->total() / 100) * 20
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'checkout',[
                'total'=>$this->total()
            ]);
        }
    }
}

    public function action ()
    {
        if(isset($_POST['applycoupon'])){
            if(empty($_POST['couponcode'])){
                $this->view->render($this->viewDir . 'cart',[
                    'message'=>"Enter coupon code.",
                    'total'=>$this->total()
                ]);
            }
            else {
                if($_POST['couponcode'] == '' || strlen(trim($_POST['couponcode'])) == 0){
                    $this->view->render($this->viewDir . 'cart',[
                        'message'=>"Enter coupon code.",
                        'total'=>$this->total()
                    ]);
                }
                else if(trim($_POST['couponcode'] == 'cyberx')) {
                    $_SESSION['couponcode'] = 1;
                    $this->view->render($this->viewDir . 'cart',[
                        'message'=>"Coupon activated!",
                        'total'=>$this->total() - ($this->total() / 100) * 20
                    ]);
                }
            }
        }
        else {
            $this->shopping_cart();
        }
    }

    public function clear ()
    {
        if(isset($_POST['clear'])){
            if(!empty($_SESSION['cart'])){
                unset($_SESSION['cart']);
                $this->shopping_cart();
            }
            else {
                $this->shopping_cart();
            }
        }
        else {
            $this->shopping_cart();
        }
    }
}