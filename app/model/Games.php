<?php 

class Games 
{
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

    public function gameCount()
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
}