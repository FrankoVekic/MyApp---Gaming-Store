<?php 

class News 
{
    public static function newsCountSearch($search)
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(a.id) from news a where headline like :search;'
            );
            $search = '%' . $search . '%';
            $query->bindParam('search',$search);
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function newsCount()
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(id) from news;'
            );
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function findNews($page)
    {
        $spp = App::config('spp');
        $from = $page * $spp - $spp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM news limit :from,:spp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('spp',$spp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findNewsSearch($page,$search)
    {
        $spp = App::config('spp');
        $from = $page * $spp - $spp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM news where headline like :search limit :from,:spp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('spp',$spp, PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function newsDetail($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM news WHERE id = $id;"
        );

        $query->execute();

        return $query->fetch();
    }
}