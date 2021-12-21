<?php 

class Blog 
{
    public static function findBlog($page)
    {
        $bpp = App::config('bpp');
        $from = $page * $bpp - $bpp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM blog order by blogdate asc limit :from,:bpp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('bpp',$bpp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findBlogSearch($page,$search)
    {
        $bpp = App::config('bpp');
        $from = $page * $bpp - $bpp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM blog where title like :search order by blogdate asc limit :from,:bpp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('bpp',$bpp, PDO::PARAM_INT);
        $query->bindParam('search',$search);
        $query->execute();

        return $query->fetchAll();
    }

    public static function blogCountSearch($search)
    {
        {
            $conn = DB::connect();
            $query = $conn->prepare(
                'SELECT count(a.id) from blog a where title like :search order by blogdate asc;'
            );
            $search = '%' . $search . '%';
            $query->bindParam('search',$search);
            $query->execute();
            return $query->fetchColumn();
        }
    }

    public static function blogDetail($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM blog WHERE id = $id;"
        );

        $query->execute();

        return $query->fetch();
    }

    public static function latestBlog()
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM blog order by id desc limit 3;');
        $query->execute();
        return $query->fetchAll();
    }

    public static function findAuthor($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT  concat(b.name,' ',b.surname)
            FROM blog a inner join user b on b.id = a.author where a.id = $id;"
        );

        $query->execute();

        return $query->fetchColumn();
    }

    public static function blogExists($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM blog WHERE id = $id"
        );
        $query->execute();
        $blogExists = $query->fetch();

        if($blogExists==null){
            return null;
        }
        return $blogExists;
    }

    public static function commentCount($id)
    {
          $conn = DB::connect();
            $query = $conn->prepare(
                "SELECT count(id) from comment where post = $id;"
            );
            $query->execute();
            return $query->fetchColumn();
    }

    public static function findComment($id,$page)
    {
        $npp = App::config('npp');
        $from = $page * $npp - $npp;
        $conn = DB::connect();
        $query = $conn->prepare("SELECT concat(a.name,' ', a.surname) as writer,b.id as id, b.comment as comment, b.commentDate as date
        from user a 
        inner join comment b on b.writer = a.id 
        inner join blog c on c.id = b.post 
        where b.post =$id order by b.commentDate asc limit :from,:npp;");

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('npp',$npp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function getCommentWriter($id)
    {
       
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT concat(a.name,' ', a.surname) as writer, b.comment as comment, b.commentDate as date
            from user a 
            inner join comment b on b.writer = a.id 
            inner join blog c on c.id = b.post 
            where b.post =$id;"
        );

        $query->execute();

        return $query->fetchAll();
    }

    public static function insertComment($writer,$comment,$post)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "INSERT INTO comment (writer,comment,post)
            VALUES (:writer, :comment, :post); "
        );
        $query->bindParam(":writer",$writer);
        $query->bindParam(":comment",$comment);
        $query->bindParam(":post",$post);
        $query->execute();
    }

    public static function myComment($commentId,$userId)
    {
        $conn = DB::connect();
        $query = $conn->prepare("
        select * from comment where writer = $userId and id=$commentId;
        ");
        $query->execute();
        $user = $query->fetch();

        if($user==null){
            return null;
        }
        else {
            return $user;
        }
    }
}