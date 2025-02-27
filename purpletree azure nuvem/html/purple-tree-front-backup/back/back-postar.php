<?php

require '../../db.php';

if (!isset($_GET['id'])) {
    $post_pai = '';
} else {$post_pai = $_GET['id'];}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    session_start();

    $conteudo = $_POST['conteudo'];
    $username = $_SESSION['username'];

    $conteudo = mysqli_real_escape_string($c, $conteudo);

    if ($post_pai == '') {
        $postar = "insert into Post(conteudo, username)  values ('$conteudo', '$username')";
    } else {
        $postar = "insert into Post(conteudo, username, id_postcomentado)  values ('$conteudo', '$username', $post_pai)";
    }

    if(mysqli_query($c, $postar)) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Erro: " . $sql . "<br>" . mysqli_error($c);
    }
}

mysqli_close($c);

?>
