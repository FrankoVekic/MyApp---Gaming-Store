<?php 

class Comment {

    public static function delete($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        DELETE FROM comment where id = $id;
        ");
        $query->execute();
    }

    public static function commentExists($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM comment WHERE id = $id"
        );
        $query->execute();
        $commentExists = $query->fetch();

        if($commentExists==null){
            return null;
        }
        return $commentExists;
    }
}