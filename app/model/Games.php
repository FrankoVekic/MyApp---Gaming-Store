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
        $query = $conn->prepare('SELECT * FROM game limit :from,:epp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findGamesSearch($page,$search)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game where name like :search limit :from,:epp;');
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
}