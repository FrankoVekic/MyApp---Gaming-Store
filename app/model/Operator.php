<?php 

class Operator 
{
    public static function authorize($email,$password)
    {
        $conn = DB::connect();
        $query = $conn->prepare('SELECT * FROM users WHERE email=:email');
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
}