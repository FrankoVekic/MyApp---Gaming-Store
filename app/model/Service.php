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
        $query = $conn->prepare('SELECT * FROM service where title like :search limit :from,:npp;');
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

    public static function latestService($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service where id !=$id order by id desc limit 2;");
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
                'SELECT count(a.id) from service a where title like :search;'
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

    public static function sideBarServices()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service order by RAND() limit 5;");
        $query->execute();
        return $query->fetchAll();
    }

    public static function serviceExistsByName($title)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM service WHERE title = '$title';"
        );
        $query->execute();
        $serviceExists = $query->fetch();

        if($serviceExists==null){
            return null;
        }
        return $serviceExists;
    }

    public static function create($params)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        INSERT INTO service(title,smalldesc,description,image) 
        VALUES (:title,:smalldesc,:description,'servicesimage.jpg');
        ");
        $query->execute($params);
    }

    public static function delete($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        DELETE FROM service WHERE id ='$id';
        ");
        $query->execute();
    }
}