<?php 

class Service 
{
    public static function getService()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM service;");
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
}