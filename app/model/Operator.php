<?php 

class Operator 
{
    public static function authorize($email,$password)
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM user WHERE email=:email');
        $query->execute(['email'=>$email]);
        $users = $query->fetch();

        if($users==null){
            return null;
        }
        if(!password_verify($password,$users->password)){
            return null;
        }
        unset($users->$password);
        return $users;     
    }

    public static function registration($name,$surname,$email,$password)
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM users WHERE email=:email');
        $query->execute(['email'=>$email]);
        $users = $query->fetch();
        
        if($users!=null){
            return false;
        }
        else {
        $passwordhash = password_hash($password,PASSWORD_BCRYPT);
        $query = $conn->prepare("INSERT INTO users (email,password,name,surname,role) VALUES (:email,:password,:name,:surname,'oper');");
        $query->bindParam(":email",$email);
        $query->bindParam(":name",$name);
        $query->bindParam(":surname",$surname);
        $query->bindParam(":password",$passwordhash);
        $query->execute();
        return true;
        }
    }
}