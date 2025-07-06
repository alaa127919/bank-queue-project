<?php

$dsn= 'mysql:host=localhost;dbname=bank-queue-db';
$user = 'root';
$pass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
    $con= new PDO($dsn,$user,$pass,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}

catch(PDOExcption $e){
    echo 'Filed to connect'. $e->getMessage();
}