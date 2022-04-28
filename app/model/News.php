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
        $npp = App::config('npp');
        $from = $page * $npp - $npp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM news order by id desc limit :from,:npp;');

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('npp',$npp, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    public static function findNewsSearch($page,$search)
    {
        $npp = App::config('npp');
        $from = $page * $npp - $npp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM news where headline like :search limit :from,:npp;');
        $search = '%' . $search . '%';

        $query->bindValue('from',$from, PDO::PARAM_INT);
        $query->bindValue('npp',$npp, PDO::PARAM_INT);
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

    public static function findAuthor($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT  concat(b.name,' ',b.surname)
            FROM news a inner join user b on b.id = a.author where a.id = $id;"
        );

        $query->execute();

        return $query->fetchColumn();
    }

    public static function newsExists($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare(
            "SELECT * FROM news WHERE id = $id"
        );
        $query->execute();
        $newsExists = $query->fetch();

        if($newsExists==null){
            return null;
        }
        return $newsExists;
    }

    public static function latestNews($id)
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM news where id !=$id order by id desc limit 2;");
        $query->execute();
        $news = $query->fetchAll();
        if($news == null){
            return null;
        }
        else {
            return $news;
        }
    }

    public static function latestNewsHome()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM news order by id desc limit 3;");
        $query->execute();
        $news = $query->fetchAll();
        if($news == null){
            return null;
        }
        else {
            return $news;
        }
    }

    public static function update($params,$img){
        $conn = DB::connect();

        $query = $conn->prepare("update news set
         headline=:headline,
         text=:text,
         author=:author
         image='$img'
         where id=:id");
         $query->execute($params);
    }

    public static function delete($id){
        $conn = DB::connect();
        $query = $conn->prepare("
        DELETE FROM news WHERE id ='$id';
        ");
        $query->execute();
    }

    public static function sideBarNews()
    {
        $conn = DB::connect();
        $query = $conn->prepare("SELECT * FROM news order by id desc limit 4;");
        $query->execute();
        return $query->fetchAll();
    }

    public static function create($params,$img){
        $conn = DB::connect();
        $query = $conn->prepare("
        INSERT INTO news(headline,text,image,author) VALUES (:headline, :text, '$img', :author);");
        $query->execute($params);
    }
}