<?php 

class Equipment 
{
    public static function newEquipment()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment order by id desc limit 4;');
        $query->execute();
        return $query->fetchAll();
    }

    public static function equipmentCount()
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            'SELECT count(id) from equipment;'
        );
        
        $query->execute();
        return $query->fetchColumn();
    }

    public static function readEquipment($page)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment limit :from,:epp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }
}