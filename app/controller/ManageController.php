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
        $this->game->console="1";
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

        if(true){
            $this->game->price = str_replace(array('.', ','), array('', '.'), 
            $this->game->price);
            Games::create((array)($this->game));
            $this->games();
        }
        else {
            $this->view->render($this->viewDir . 'new_game',[
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

    public function equipment()
    {
        $this->view->render($this->viewDir . 'equipment');
    }

    public function services ()
    {
        $this->view->render($this->viewDir . 'services');
    }
}