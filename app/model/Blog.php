<?php 

class Blog 
{
    public static function findBlog($page)
    {
        $bpp = App::config('bpp');
        $from = $page * $bpp - $bpp;
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM blog limit :from,:bpp;');

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
        $query = $conn->prepare('SELECT * FROM blog where title like :search limit :from,:bpp;');
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
                'SELECT count(a.id) from blog a where title like :search;'
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
}