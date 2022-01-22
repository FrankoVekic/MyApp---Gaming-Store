<?php 

class Games 
{
    public static function latestGames()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game order by id desc limit 3;');
        $query->execute();
        return $query->fetchAll();
    }

    public static function findGames($page)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game order by id desc limit :from,:epp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->execute();
        
        $gameExists = $query->fetchAll();

        if($gameExists==null){
            return null;
        }
        return $gameExists;
    }

    public static function findGamesAdmin($page)
    {
        $spp = App::config('spp');
        $from = $page * $spp - $spp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game order by id desc limit :from,:spp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('spp',$spp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findGamesSearch($page,$search)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game where name like :search order by id desc limit :from,:epp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function gameCount()
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(id) from game;'
            );
            $query->execute();
            
            return $query->fetchColumn();
        }
    }

    public static function gameCountSearch($search)
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(a.id) from game a where name like :search;'
            );
            $search = '%' . $search . '%';
            $query->bindParam('search',$search);
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function gameDetail($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM game WHERE id = $id;"
        );

        $query->execute();

        return $query->fetch();
    }

    public static function gameExists($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM game WHERE id = $id"
        );
        $query->execute();
        $gameExists = $query->fetch();

        if($gameExists==null){
            return null;
        }
        return $gameExists;
    }

    public static function gameExistsByName($name)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM game WHERE name = '$name';"
        );
        $query->execute();
        $gameExists = $query->fetch();

        if($gameExists==null){
            return null;
        }
        return $gameExists;
    }

    public static function checkNameForEdit($name,$id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM game WHERE name = '$name' and id != $id;"
        );
        $query->execute();
        $nameExists = $query->fetch();

        if($nameExists==null){
            return null;
        }
        return $nameExists;
    }

    public static function create($params)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        INSERT INTO game(name,price,smalldesc,description,
        quantity,memory_required,console,image) VALUES (:name,:price,:smalldesc,:description,
        :quantity,:memory_required,:console,'noimg.png');
        ");
        $query->execute($params);
    }

    public static function delete($name)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        DELETE FROM game WHERE name ='$name';
        ");
        $query->execute();
    }

    public static function update ($params,$img)
    {
        $conn = DB::connect();

        $query = $conn->prepare("update game set
         name=:name,
         price=:price,
         smalldesc =:smalldesc,
         description=:description,
         quantity=:quantity,
         memory_required=:memory_required,
         console=:console,
         image='$img'
         where id=:id");
         $query->execute($params);
    }

    public static function randomGame($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM game where id !=$id order by RAND() limit 3;");
        $query->execute();
        return $query->fetchAll();
    }
}