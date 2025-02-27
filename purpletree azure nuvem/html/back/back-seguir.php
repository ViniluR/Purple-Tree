<?php
include '../db.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET["id1"]) && isset($_GET["id2"])){
    $username_1 = $_GET['id1'];
    $segueusername_2 = $_GET['id2'];

    $sql = "SELECT * FROM segue WHERE username_1 = '$username_1' AND segueusername_2 = '$segueusername_2'";

    $segue = mysqli_query($c, $sql);

    $segue = mysqli_fetch_assoc($segue);

    if ($segue) {
        $sql_delete = "DELETE FROM segue WHERE username_1 = '$username_1' AND segueusername_2 = '$segueusername_2'";
        mysqli_query($c, $sql_delete);
    } else {
        $sql_create = "INSERT INTO segue (username_1, segueusername_2) VALUES ('$username_1', '$segueusername_2')";
        mysqli_query($c, $sql_create);
    }

    header("Location: ../perfil.php?username=$segueusername_2");
}

echo "{$_GET['id1']}";
echo "{$_GET['id2']}";
?>
