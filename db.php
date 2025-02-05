<?php
$servername = "purple_tree-db";
$username = "admin";
$password = "admin";
$dbname = "purple_tree";

$c = mysqli_connect($servername, $username, $password, $dbname);

if(!$c){
    die("Conexão falhou: " . mysqli_connect_error());
}
?>
