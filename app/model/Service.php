<?php 

class Service 
{
    public static function getService($page)
    {
        $spp = App::config('spp');
        $from = $page * $spp - $spp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM service limit :from,:spp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('spp',$spp, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }

    public static function getServiceSearch($page,$search)
    {
        $npp = App::config('npp');
        $from = $page * $npp - $npp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM service where name like :search limit :from,:npp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('npp',$npp, PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findService($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service WHERE id = $id;");
        $query->execute();
        return $query->fetch();
    }

    public static function latestService()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service order by id desc limit 2;");
        $query->execute();
        return $query->fetchAll();
    }

    public static function serviceCount()
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            'SELECT count(id) from service;'
        );
        
        $query->execute();
        return $query->fetchColumn();
    }

    public static function serviceCountSearch($search)
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(a.id) from service a where name like :search;'
            );
            $search = '%' . $search . '%';
            $query->bindParam('search',$search);
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function randomService()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service order by RAND() limit 1;");
        $query->execute();
        return $query->fetch();
    }
}