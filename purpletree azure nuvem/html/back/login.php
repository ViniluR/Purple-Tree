<?php
session_start();
require '../db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST['username'];
    $senha = $_POST['senha'];

    $username = mysqli_real_escape_string($c, $username);
    $senha = mysqli_real_escape_string($c, $senha);

    $teste = "SELECT * FROM Usuario WHERE username = '$username'";
    $result = mysqli_query($c, $teste);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($senha, $row['senha'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: ../index.php");
            exit;
        } else {
            echo "Nome de usuário ou senha incorretos.";
        }
    } else {
        echo "Nome de usuário ou senha incorretos.";
    }
} else {
    header("Location: ./login.php");
    exit;
}
?>
