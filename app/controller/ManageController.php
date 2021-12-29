<?php 

class ManageController extends AdminController
{
    private $viewDir = 'manage' . DIRECTORY_SEPARATOR;

    private $game;
    private $message;

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
            $name = $_GET['game'];
            if(Games::gameExistsByName($name) == null){
                $this->games();
            }
            else {
                Games::delete($name);
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
        if(strlen(trim($this->game->smalldesc))>60){
            $this->message="Max number of letters is 60.";
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
        if(strlen(trim($this->game->description)) === 0){
            $this->message="Description is required.";
            return false;
        }
        if(strlen(trim($this->game->description))<60){
            $this->message="Description is too short.";
            return false;
        }
        if(strlen(trim($this->game->description))>3000){
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
        $this->view->render($this->viewDir . 'equipment');
    }

    public function services ()
    {
        $this->view->render($this->viewDir . 'services');
    }
}