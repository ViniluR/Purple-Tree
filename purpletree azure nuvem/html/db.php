<?php
$servername = "ics-dbpurpletree.mysql.database.azure.com";
$username = "purpletreedb";
$password = "Rafad1234@";
$dbname = "ics-dbpurpletree";

$c = mysqli_connect($servername, $username, $password, $dbname);

if(!$c){
    die("Conexão falhou: " . mysqli_connect_error());
}
?>
