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

    public static function equipmentCountSearch($search)
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(a.id) from equipment a where name like :search;'
            );
            $search = '%' . $search . '%';
            $query->bindParam('search',$search);
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function readEquipment($page)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment order by id desc limit :from,:epp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function readEquipmentManageTable($search)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM equipment WHERE name like :search");
        $search = '%' . $search . '%';
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findEquipmentSearch($page,$search)
    {
        $epp = App::config('epp');
        $from = $page * $epp - $epp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment where name like :search limit :from,:epp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('epp',$epp, PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function equipmentDetail($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM equipment WHERE id = $id;"
        );

        $query->execute();

        return $query->fetch();
    }

    public static function latestEquipment()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment order by id desc limit 3;');
        $query->execute();
        return $query->fetchAll();
    }

    public static function randomEquipment($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM equipment where id != $id order by RAND() limit 3;");
        $query->execute();
        return $query->fetchAll();
    }

    public static function equipmentExists($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM equipment WHERE id = $id"
        );
        $query->execute();
        $equipmentExists = $query->fetch();

        if($equipmentExists==null){
            return null;
        }
        return $equipmentExists;
    }

    public static function findEquipmentAdmin($page)
    {
        $spp = App::config('spp');
        $from = $page * $spp - $spp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment limit :from,:spp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('spp',$spp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function getAllEquipment()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM equipment;');
        $query->execute();

        return $query->fetchAll();
    }

    public static function equipmentExistsByName($name)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM equipment WHERE name = '$name';"
        );
        $query->execute();
        $productExists = $query->fetch();

        if($productExists==null){
            return null;
        }
        return $productExists;
    }

    public static function create($params)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        INSERT INTO equipment(name,price,smalldesc,description,
        quantity,image) VALUES (:name,:price,:smalldesc,:description,
        :quantity,'noimg.png');
        ");
        $query->execute($params);
    }

    public static function update ($params,$img)
    {
        $conn = DB::connect();

        $query = $conn->prepare("update equipment set
         name=:name,
         price=:price,
         smalldesc =:smalldesc,
         description=:description,
         quantity=:quantity,
         image='$img'
         where id=:id");
         $query->execute($params);
    }

    public static function delete($name)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        DELETE FROM equipment WHERE name ='$name';
        ");
        $query->execute();
    }
}