<?php 
// DB connection

function connection()
{
    $credentials=array('host' => "46.101.221.106", 'db' => "sequalize" , 'user' => "root" , 'password' => "testplatformdm");
    $dsn = "mysql:host={$credentials['host']};dbname={$credentials['db']}";
    $user = $credentials['user'];
    $password = $credentials['password'];

    try {
        $con = new PDO($dsn, $user, $password);
       // echo "Successfully connected";
        return $con;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>