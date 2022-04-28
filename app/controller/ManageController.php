<?php 

class ManageController extends AdminController
{
    private $viewDir = 'manage' . DIRECTORY_SEPARATOR;
    private $imgDir = BP . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'shop' . DIRECTORY_SEPARATOR;
    private $imgDirService = BP . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR;

    private $game;
    private $message;
    private $equipment;
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->game = new stdClass();
        $this->game->name="";
        $this->game->price="0,00";
        $this->game->smalldesc ="";
        $this->game->description = "";
        $this->game->quantity="0";
        $this->game->memory_required="0";
        $this->game->console="Select Console";
        $this->game->image="";

        $this->equipment = new stdClass();
        $this->equipment->name="";
        $this->equipment->price="0,00";
        $this->equipment->smalldesc = "";
        $this->equipment->description="";
        $this->equipment->quantity="0";
        $this->equipment->image="";

        $this->service = new stdClass();
        $this->service->title="";
        $this->service->smalldesc="";
        $this->service->description="";
        $this->service->image="";

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

        if(Games::gameCount() != 0){
            $gameCount = Games::gameCount();
            $pageCount = ceil($gameCount/App::config('spp'));
        }
        else {
            $pageCount = 1;
        }

        

        if($page>$pageCount){
            $page=$pageCount;
        }

        $newGame = '<div class="col-md-4 service_blog margin_bottom_50">
        <div class="full">
          <div class="service_img"> <img class="img-responsive" src="/public/images/shop/new.jpg" alt="#" /> </div>
          <div class="service_cont">
            <h3 class="service_head">Add New Game</h3>
            <p>Add a new game. You must fill in the appropriate information in order to enter the game in the shop.</p>
            <div class="bt_cont"> <a style="margin-right:5px; background-color:seagreen" class="btn sqaure_bt btn-lg btn-block" href="new_game">Add</a> </div>
          </div>
        </div>
      </div>';
        
        if(trim($search)=='' || strlen(trim($search))==0 || empty($_GET['search'])){

            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGamesAdmin($page),
                'page'=>$page,
                'pageCount'=>$pageCount,
                'search'=>$search,
                'message'=>'',
                'newGame'=>$newGame
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGames($page),
                'page'=>$page,
                'pageCount'=>$pageCount,
                'search'=>$search,
                'message'=>''
            ]);
        }
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

        $newGame = '<div class="col-md-4 service_blog margin_bottom_50">
        <div class="full">
          <div class="service_img"> <img class="img-responsive" src="/public/images/shop/new.jpg" alt="#" /> </div>
          <div class="service_cont">
            <h3 class="service_head">Add New Game</h3>
            <p>Add a new game. You must fill in the appropriate information in order to enter the game in the shop.</p>
            <div class="bt_cont"> <a style="margin-right:5px; background-color:seagreen" class="btn sqaure_bt" href="new_game">Add</a> </div>
          </div>
        </div>
      </div>';

      $backButton = '<div class="row">      
      <div style="margin-left:50px;" class="bt_cont"><a class="btn sqaure_bt" href="games">Back</a></div>
      </div>';

        if(Games::findGamesSearch($page,$search) == null)
        {
            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGamesSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
                'backButton'=>$backButton
            ]);
        }
        else if(strlen(trim($search))==0 || trim($search)==''){
            $this->view->render($this->viewDir . 'games',[
                'games'=>Games::findGamesSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"Search results for: " . '\'' . $search . '\'',
                'newGame'=>$newGame
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

    public function new_game()
    {
        $this->view->render($this->viewDir . 'new_game',[
            'game'=>$this->game,
            'message'=>'Enter required information.'
        ]);
    }

    public function edit_game()
    {
        if(!isset($_GET['game'])){
            $this->games();
        }

        if(Games::gameExistsByName($_GET['game']) == null){
            $this->games();
        }
        else {
            $game = Games::gameExistsByName($_GET['game']);
            $this->view->render($this->viewDir . 'edit',[
                'game'=>$game,
                'message'=>'Change information about ' . $game->name
            ]);
        }
    }

    public function edit_equipment()
    {
        if(!isset($_GET['product'])){
            $this->equipment();
        }

        if(Equipment::equipmentExistsByName($_GET['product']) == null){
            $this->equipment();
        }
        else {
            $equipment = Equipment::equipmentExistsByName($_GET['product']);
            $this->view->render($this->viewDir . 'edit_product',[
                'equipment'=>$equipment,
                'message'=>'Change information about ' . $equipment->name . '.'
            ]);
        }
    }

    public function change_game_data()
    {
        if(!$_POST){
            $this->games();
            return;
        }

        if(Games::gameExists($_POST['id']) == null){
            $this->games();
            return;
        }

        $this->game=(object)$_POST;

        if($this->verify_name_edit() && 
           $this->verify_price() &&
           $this->verify_smalldesc() && 
           $this->verify_description() && 
           $this->verify_quantity() && 
           $this->verify_memoryRequired() &&
           $this->verify_console()        
        ){
            $file = $_FILES['image']['name'];
            if (!$file){
                $img = $_SESSION['image'];
                Games::update((array)($this->game),$img);
                unset($_SESSION['image']);
            }else {
                $img = $this->game->id . '.jpg';
                Games::update((array)($this->game),$img);
        }
            if(isset($_FILES['image'])){
                move_uploaded_file($_FILES['image']['tmp_name'],
                $this->imgDir . $this->game->id . '.jpg');
            }
            $this->games();
        }
        else {
            $this->view->render($this->viewDir . 'edit',[
                'game'=>$this->game,
                'message'=>$this->message
            ]);
        }
    }

    public function add_game()
    {
        if(!$_POST){
            $this->new_game();
            return;
        }

        $this->game=(object)$_POST;

        if($this->verify_name() && 
           $this->verify_price() &&
           $this->verify_smalldesc() && 
           $this->verify_description() && 
           $this->verify_quantity() && 
           $this->verify_memoryRequired() &&
           $this->verify_console()        
        ){
            $this->game->price = str_replace(array('.', ','), array('', '.'), 
            $this->game->price);
            Games::create((array)($this->game));
            $this->games();
        }
        else {
            $this->view->render($this->viewDir . 'new_game',[
                'game'=>$this->game,
                'message'=>$this->message
            ]);
        }
    }

    public function delete()
    {
        if(!isset($_GET['game'])){
            $this->games();
        }
        else {
            $id = $_GET['game'];
            if(Games::gameExistsById($id) == null){
                $this->games();
            }
            else {
                Games::delete($id);
                $this->games();
            }
        }
    }

    private function verify_name()
    {
        if(!isset($this->game->name)){
            $this->message = "Name is required";
            return false;
        }
        if(strlen(trim($this->game->name)) === 0){
            $this->message="Name is required.";
            return false;
        }
        if(Games::gameExistsByName($this->game->name) !=null){
            $this->message="This Name is already in use.";
            return false;
        }
        if(strlen(trim($this->game->name))<3){
            $this->message="Name is too short (atleast 3 letters).";
            return false;
        }
        if(strlen(trim($this->game->name))>50){
            $this->message="Max number of letters is 50.";
            return false;
        }
        if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;"\<\>,\?\\\]/', $this->game->name)){
            $this->message="You can only write letters and numbers.";
            return false;
        }
        if(!preg_match("/[a-z0-9.]/i", $this->game->name)){
            $this->message="You didn't enter any letters or numbers.";
            return false;
        }
        return true;
    }

    private function verify_name_edit()
    {
        if(!isset($this->game->name)){
            $this->message = "Name is required";
            return false;
        }
        if(strlen(trim($this->game->name)) === 0){
            $this->message="Name is required.";
            return false;
        }
        if(strlen(trim($this->game->name))<3){
            $this->message="Name is too short (atleast 3 letters).";
            return false;
        }
        if(strlen(trim($this->game->name))>50){
            $this->message="Max number of letters is 50.";
            return false;
        }
        if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;"\<\>,\?\\\]/', $this->game->name)){
            $this->message="You can only write letters and numbers.";
            return false;
        }
        if(!preg_match("/[a-z0-9.]/i", $this->game->name)){
            $this->message="You didn't enter any letters or numbers.";
            return false;
        }
        return true;
    }
    private function verify_price()
    {
        if(!isset($this->game->price)){
            $this->message="Price is required";
            return false;
        }
        if(strlen(trim($this->game->price))===0){
            $this->message="Price is required";
            return false;
        }
        $num=(float) str_replace(array('.', ','), array('', '.'), 
        $this->game->price);
        
        if($num<=0){
            $this->message="Price must be a possitive number";
            return false;
        }
        return true;
    }

    private function verify_smalldesc()
    {

        if(!isset($this->game->smalldesc)){
            $this->message = "Short Description is required.";
            return false;
        }
        if(strlen(trim($this->game->smalldesc)) === 0){
            $this->message="Short Description is required.";
            return false;
        }
        if(strlen(trim($this->game->smalldesc))<10){
            $this->message="Short Description is too short.";
            return false;
        }
        $catchWords = explode(' ',trim($this->game->smalldesc));
        if(strlen($catchWords[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->game->smalldesc))>250){
            $this->message="Max number of letters in Short Description is 250.";
            return false;
        }
        if(preg_match('/[\'\/~`\#\%\^\*\(\)_\+=\{\}\[\]\|;"\<\>\?\\\]/', $this->game->smalldesc)){
            $this->message="Some of the characters you wrote are not allowed to be in the Short Description.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->game->smalldesc)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    private function verify_description()
    {

        if(!isset($this->game->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(empty($this->game->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(strlen(trim($this->game->description)) === 0){
            $this->message="Description is required.";
            return false;
        }
        if(strlen(trim($this->game->description))<30){
            $this->message="Description is too short.";
            return false;
        }
        $catchWord = explode(' ',trim($this->game->description));
        if(strlen($catchWord[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->game->description))>5000){
            $this->message="Description is too big.";
            return false;
        }
        if(preg_match('/[\'\/~`\#\%\^\*\(\)_\+=\{\}\[\]\|;"\<\>\\\\]/', $this->game->description)){
            $this->message="Some of the characters you wrote are not allowed to be in the Description.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->game->description)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    private function verify_quantity(){
       
        if(!isset($this->game->quantity)){
            $this->message="Quantity is required";
            return false;
        }
        if(strlen(trim($this->game->quantity))===0){
            $this->message="Quantity is required";
            return false;
        }
        $num=(float) str_replace(array('.', ','), array('', '.'), 
        $this->game->quantity);
        
        if($num<=0){
            $this->message="Quantity must be a possitive number";
            return false;
        }
        return true;
    }

    private function verify_memoryRequired()
    {
        if(!isset($this->game->memory_required)){
            $this->message="Required Memory is required.";
            return false;
        }
        if(strlen(trim($this->game->memory_required))===0){
            $this->message="You didin't enter the required memory";
            return false;
        }
        $num=(float) str_replace(array('.', ','), array('', '.'), 
        $this->game->memory_required);
        
        if($num<=0){
            $this->message="Memory required must be a possitive number";
            return false;
        }
        return true;
    }

    private function verify_console()
    {
        if($_POST['console'] != 'PC' && $_POST['console'] !='PS5' && $_POST['console'] != 'Xbox'){
            $this->message="Console is required.";
            return false;
        }
        return true;
    }

    public function equipment()
    {

        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }
        
        if(trim($search)=='' || strlen(trim($search))==0 || empty($_GET['search'])){

            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::readEquipmentManageTable($search),
                'search'=>$search,
                'message'=>''
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::readEquipmentManageTable($search),
                'search'=>$search,
                'message'=>''
            ]);
        }
    }

    public static function checkSearchGames()
    {
        $url = '';
        if(!isset($_GET['search']) || strlen(trim($_GET['search'])) == 0){
            $url = 'games';
        }
        else 
        {
            $url = 'request_game';
        }
        return $url;
    }

    public static function checkSearchEquipment()
    {
        $url = '';
        if(!isset($_GET['search']) || strlen(trim($_GET['search'])) == 0){
            $url = 'equipment';
        }
        else 
        {
            $url = 'request_equipment';
        }
        return $url;
    }

    public function request_equipment()
    {
     
        if(!isset($_GET['search'])){
            $search='';
        }else {
            $search = $_GET['search'];
        }

        if(Equipment::readEquipmentManageTable($search) == null)
        {
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::readEquipmentManageTable($search),
                'search'=>$search,
                'message'=>"No results for: " . '\'' . $search . '\''
            ]);
        }
        else if(strlen(trim($search))==0 || trim($search)==''){
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::readEquipmentManageTable($search),
                'search'=>$search
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'equipment',[
                'equipment'=>Equipment::readEquipmentManageTable($search),
                'search'=>$search,
            ]);
        }
    }
    public function new_equipment()
    {
        $this->view->render($this->viewDir . 'new_equipment',[
            'equipment'=>$this->equipment,
            'message'=>'Enter required information.'
        ]);
    }

    public function change_equipment_data()
    {
        if(!$_POST){
            $this->equipment();
            return;
        }

        if(Equipment::equipmentExists($_POST['id']) == null){
            $this->equipment();
            return;
        }

        $this->equipment=(object)$_POST;

        if($this->verify_name_edit_product() && 
           $this->verify_price_product() &&
           $this->verify_smalldesc_product() && 
           $this->verify_description_product() && 
           $this->verify_quantity_product()      
        ){
            $file = $_FILES['image']['name'];
            if (!$file){
                $img = $_SESSION['image_product'];
                Equipment::update((array)($this->equipment),$img);
                unset($_SESSION['image_product']);
            }else {
                $img = $this->equipment->id . 'a.jpg';
                Equipment::update((array)($this->equipment),$img);
        }
            if(isset($_FILES['image'])){
                move_uploaded_file($_FILES['image']['tmp_name'],
                $this->imgDir . $this->equipment->id . 'a.jpg');
            }
            $this->equipment();
        }
        else {
            $this->view->render($this->viewDir . 'edit_product',[
                'equipment'=>$this->equipment,
                'message'=>$this->message
            ]);
        }
    }

    private function verify_name_edit_product()
    {
        if(!isset($this->equipment->name)){
            $this->message = "Name is required";
            return false;
        }
        if(strlen(trim($this->equipment->name)) === 0){
            $this->message="Name is required.";
            return false;
        }
        if(strlen(trim($this->equipment->name))<3){
            $this->message="Name is too short (atleast 3 letters).";
            return false;
        }
        if(strlen(trim($this->equipment->name))>50){
            $this->message="Max number of letters is 50.";
            return false;
        }
        if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;"\<\>,\?\\\]/', $this->equipment->name)){
            $this->message="You can only write letters and numbers.";
            return false;
        }
        if(!preg_match("/[a-z0-9.]/i", $this->equipment->name)){
            $this->message="You didn't enter any letters or numbers.";
            return false;
        }
        return true;
    }
    private function verify_price_product()
    {
        if(!isset($this->equipment->price)){
            $this->message="Price is required";
            return false;
        }
        if(strlen(trim($this->equipment->price))===0){
            $this->message="Price is required";
            return false;
        }
        $num=(float) str_replace(array('.', ','), array('', '.'), 
        $this->equipment->price);
        
        if($num<=0){
            $this->message="Price must be a possitive number";
            return false;
        }
        return true;
    }

    private function verify_smalldesc_product()
    {

        if(!isset($this->equipment->smalldesc)){
            $this->message = "Short Description is required.";
            return false;
        }
        if(strlen(trim($this->equipment->smalldesc)) === 0){
            $this->message="Short Description is required.";
            return false;
        }
        if(strlen(trim($this->equipment->smalldesc))<10){
            $this->message="Short Description is too short.";
            return false;
        }
        $catchWords = explode(' ',trim($this->equipment->smalldesc));
        if(strlen($catchWords[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->equipment->smalldesc))>250){
            $this->message="Max number of letters in Short Description is 250.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->equipment->smalldesc)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    private function verify_description_product()
    {
        if(!isset($this->equipment->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(empty($this->equipment->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(strlen(trim($this->equipment->description)) === 0){
            $this->message="Description is required.";
            return false;
        }
        if(strlen(trim($this->equipment->description))<30){
            $this->message="Description is too short.";
            return false;
        }
        $catchWord = explode(' ',trim($this->equipment->description));
        if(strlen($catchWord[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->equipment->description))>5000){
            $this->message="Description is too big.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->equipment->description)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    private function verify_quantity_product(){
       
        if(!isset($this->equipment->quantity)){
            $this->message="Quantity is required";
            return false;
        }
        if(strlen(trim($this->equipment->quantity))===0){
            $this->message="Quantity is required";
            return false;
        }
        $num=(float) str_replace(array('.', ','), array('', '.'), 
        $this->equipment->quantity);
        
        if($num<=0){
            $this->message="Quantity must be a possitive number";
            return false;
        }
        return true;
    }

    public function add_product()
    {
        if(!$_POST){
            $this->new_equipment();
            return;
        }

        $this->equipment=(object)$_POST;

        if($this->verify_name_edit_product() && 
           $this->verify_price_product() &&
           $this->verify_smalldesc_product() && 
           $this->verify_description_product() && 
           $this->verify_quantity_product()     
        ){
            $this->equipment->price = str_replace(array('.', ','), array('', '.'), 
            $this->equipment->price);
            Equipment::create((array)($this->equipment));
            $this->equipment();
        }
        else {
            $this->view->render($this->viewDir . 'new_equipment',[
                'equipment'=>$this->equipment,
                'message'=>$this->message
            ]);
        }
    }

    public function remove()
    {
        if(!isset($_GET['product'])){
            $this->equipment();
        }
        else {
            $id = $_GET['product'];
            if(Equipment::equipmentExistsById($id) == null){
                $this->equipment();
            }
            else {
                Equipment::delete($id);
                $this->equipment();
            }
        }
    }

    public function services()
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

        if(Service::serviceCount()!=0){
            $serviceCount = Service::serviceCount();
            $pageCount = ceil($serviceCount/App::config('spp'));
        }
        else {
            $pageCount = 1;
        }

        if($page>$pageCount){
            $page=$pageCount;
        }

        $newService = '<div class="col-md-4 service_blog margin_bottom_50">
        <div class="full">
          <div class="service_img"> <img class="img-responsive" src="/public/images/shop/newService.jpg" alt="#" /> </div>
          <div class="service_cont">
            <h3 class="service_head">Add New Service</h3>
            <p>Add a service. You must fill in the appropriate information in order to enter the service to the shop.</p>
            <div class="bt_cont"> <a style="margin-right:5px; background-color:seagreen" class="btn sqaure_bt btn-lg btn-block" href="new_service">Add</a> </div>
          </div>
        </div>
      </div>';
        
        $this->view->render($this->viewDir . 'services',[
            'service'=>Service::getService($page),
            'page'=>$page,
            'pageCount'=>$pageCount,
            'random'=>Service::randomService(),
            'message'=>'',
            'search'=>$search,
            'newService'=>$newService
        ]);
    }

    public static function checkSearchService()
    {
        $url = '';
        if(!isset($_GET['search']) || strlen(trim($_GET['search'])) == 0){
            $url = 'services';
        }
        else 
        {
            $url = 'request_services';
        }
        return $url;
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
            $backButton = '<div class="row">      
                           <div style="margin-left:50px;" class="bt_cont"><a class="btn sqaure_bt" href="services">Back</a></div>
                           </div>';
            
            $this->view->render($this->viewDir . 'services',[
                'service'=>Service::getServiceSearch($page,$search),
                'page'=>$page,
                'search'=>$search,
                'pageCount'=>$pageCount,
                'message'=>"No results for: " . '\'' . $search . '\'',
                'random'=>Service::randomService(),
                'sideService'=>Service::sideBarServices(),
                'sideNews'=>News::sideBarNews(),
                'backButton'=>$backButton
            ]);
        }
        else {
            $this->view->render($this->viewDir . 'services',[
                'service'=>Service::getServiceSearch($page,$search),
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

    public function new_service()
    {
        $this->view->render($this->viewDir . 'new_service',[
            'service'=>$this->service,
            'message'=>'Enter required information.'
        ]);
    }

    public function delete_service()
    {
        if(!isset($_GET['service'])){
            $this->services();
        }
        else {
            $id = $_GET['service'];
            if(Service::findService($id) == null){
                $this->services();
            }
            else {
                Service::delete($id);
                $this->services();
            }
        }
    }

    public function add_service()
    {
        if(!$_POST){
            $this->new_service();
            return;
        }

        $this->service=(object)$_POST;

        if($this->verify_title() && 
           $this->verify_smalldesc_service() && 
           $this->verify_description_service()       
        ){
            Service::create((array)($this->service));
            $this->services();
        }
        else {
            $this->view->render($this->viewDir . 'new_service',[
                'service'=>$this->service,
                'message'=>$this->message
            ]);
        }
    }

    private function verify_title()
    {
        if(!isset($this->service->title)){
            $this->message = "Title is required";
            return false;
        }
        if(strlen(trim($this->service->title)) === 0){
            $this->message="Title is required.";
            return false;
        }
        if(strlen(trim($this->service->title))<3){
            $this->message="Title is too short (atleast 3 letters).";
            return false;
        }
        if(strlen(trim($this->service->title))>100){
            $this->message="Max number of letters is 100.";
            return false;
        }
        if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;"\<\>,\?\\\]/', $this->service->title)){
            $this->message="You can only write letters and numbers.";
            return false;
        }
        if(!preg_match("/[a-z0-9.]/i", $this->service->title)){
            $this->message="You didn't enter any letters or numbers.";
            return false;
        }
        return true;
    }


    private function verify_smalldesc_service()
    {

        if(!isset($this->service->smalldesc)){
            $this->message = "Short Description is required.";
            return false;
        }
        if(strlen(trim($this->service->smalldesc)) === 0){
            $this->message="Short Description is required.";
            return false;
        }
        if(strlen(trim($this->service->smalldesc))<10){
            $this->message="Short Description is too short.";
            return false;
        }
        $catchWords = explode(' ',trim($this->service->smalldesc));
        if(strlen($catchWords[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWords[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->service->smalldesc))>250){
            $this->message="Max number of letters in Short Description is 250.";
            return false;
        }
        if(preg_match('/[\'\/~`\#\%\^\*\(\)_\+=\{\}\[\]\|;"\<\>\?\\\]/', $this->service->smalldesc)){
            $this->message="Some of the characters you wrote are not allowed to be in the Short Description.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->service->smalldesc)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    private function verify_description_service()
    {

        if(!isset($this->service->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(empty($this->service->description)){
            $this->message = "Description is required.";
            return false;
        }
        if(strlen(trim($this->service->description)) === 0){
            $this->message="Description is required.";
            return false;
        }
        if(strlen(trim($this->service->description))<30){
            $this->message="Description is too short.";
            return false;
        }
        $catchWord = explode(' ',trim($this->service->description));
        if(strlen($catchWord[0]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[1]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen($catchWord[2]) > 25){
            $this->message="Too long words. Try writing something else.";
            return false;
        }
        if(strlen(trim($this->service->description))>7000){
            $this->message="Description is too big.";
            return false;
        }
        if(preg_match('/[\'\/~`\#\%\^\*\(\)_\+=\{\}\[\]\|;"\<\>\\\\]/', $this->service->description)){
            $this->message="Some of the characters you wrote are not allowed to be in the Description.";
            return false;
        }
        if(!preg_match("/[a-z.]/i", $this->service->description)){
            $this->message="You didn't write any letters.";
            return false;
        }
        return true;
    }

    public function edit_service()
    {
        if(!isset($_GET['service'])){
            $this->services();
        }

        if(Service::findService($_GET['service']) == null){
            $this->services();
        }
        else {
            $service = Service::findService($_GET['service']);
            $this->view->render($this->viewDir . 'edit_service',[
                'service'=>$service,
                'message'=>'Change information about ' . $service->title . '.'
            ]);
        }
    }

    public function change_service_data()
    {
        if(!$_POST){
            $this->services();
            return;
        }

        if(Service::findService($_POST['id']) == null){
            $this->services();
            return;
        }

        $this->service=(object)$_POST;

        if($this->verify_title() && 
           $this->verify_smalldesc_service() && 
           $this->verify_description_service()      
        ){
            $file = $_FILES['image']['name'];
            if (!$file){
                $img = $_SESSION['image_service'];
                Service::update((array)($this->service),$img);
                unset($_SESSION['image_service']);
            }else {
                $img = $this->service->id . '.jpg';
                Service::update((array)($this->service),$img);
        }
            if(isset($_FILES['image'])){
                move_uploaded_file($_FILES['image']['tmp_name'],
                $this->imgDirService . $this->service->id . '.jpg');
            }
            $this->services();
        }
        else {
            $this->view->render($this->viewDir . 'edit_service',[
                'service'=>$this->service,
                'message'=>$this->message
            ]);
        }
    }
}