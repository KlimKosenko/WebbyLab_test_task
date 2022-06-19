<?php
$db_user = "root";
$db_pass = "root";
$db_name = "wb_task_test";

try{
    $db = new PDO('mysql:host=localhost;dbname='.$db_name.';charset=utf8', $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed: ".$e->getMessage();
}
?>