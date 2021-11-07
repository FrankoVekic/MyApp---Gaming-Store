<?php 

class Equipment 
{
    public static function findEquipment()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment;');
        $query->execute();
        return $query->fetchAll();
    }
}