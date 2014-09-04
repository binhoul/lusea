<?php
include("config.php");

/*
    This file creates a new MySQL connection using the PDO class.
    The login details are taken from config.php.
*/
try {
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=UTF8",
        $db_user,
        $db_pass
    );

    $db->query("SET NAMES 'utf8'");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    error_log($e->getMessage());
    die("A database error was encountered");
}



        global $db;
$arr = array('cn_name' => '淡水鳄');
        if(empty($arr)){
            // random
            $st = $db->prepare("");

        }
        else if($arr['cn_name']){
            $st = $db->prepare("select * from cards where cn_name=:cn_name");
        }
        else {
            throw new Exception("no card!");
        }
        $st->execute($arr);
        // Returns an array of Category objects:
        $str = $st->fetchAll();
        var_dump($str);
        echo $str[0]['cn_name'];

