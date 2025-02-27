<?php
include '../db.php';

if (!isset($_GET['id'])) {
    die("ID do post não especificado.");
}

$id = $_GET['id'];

$sql = "DELETE FROM Post WHERE id='$id'";

if(mysqli_query($c, $sql)) {
    header("Location: ../index.php");
    exit;
} else {
    echo "Erro: " . $sql . "<br>" . mysqli_error($c);
}

mysqli_close($c);

?>
