<?php

require '../../db.php';

if (!isset($_GET['id'])) {
    die("nenhum id informado");
} else {$id_post = $_GET['id'];}


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $conteudo = $_POST['conteudo'];

    $conteudo = mysqli_real_escape_string($c, $conteudo);

    $post = mysqli_query($c, "SELECT * from Post where id = $id_post");
    $post = mysqli_fetch_assoc($post);

    $postar = "UPDATE Post set conteudo = '$conteudo' where id = $id_post";

    if(mysqli_query($c, $postar)) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Erro: " . $sql . "<br>" . mysqli_error($c);
    }
}
else {
    header("Location: ../index.php");
}

mysqli_close($c);

?>
