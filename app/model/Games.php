<?php 

class Games 
{
    public static function findGames()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM game;');
        $query->execute();
        return $query->fetchAll();
    }
}