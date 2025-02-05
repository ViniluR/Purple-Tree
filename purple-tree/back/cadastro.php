<?php
session_start();
require '../../db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

	$username = $_POST["username"];
	$nome = $_POST["nome"];
	$senha = $_POST["senha"];
	$biografia = $_POST["biografia"];

	$username = mysqli_real_escape_string($c, $username);
	$nome = mysqli_real_escape_string($c, $nome);
    $senha = mysqli_real_escape_string($c, $senha);
    $biografia = mysqli_real_escape_string($c, $biografia);

	$teste_repetidos = "SELECT * from Usuario WHERE username = '$username'";
	$lista_repetidos = mysqli_query($c, $teste_repetidos);

	if(mysqli_num_rows($lista_repetidos) == 0) {

		$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

		$sql = "INSERT into Usuario (username, nome, senha, biografia) VALUES ('$username', '$nome', '$senha_hash', '$biografia')";

		if(mysqli_query($c, $sql)) {
			$_SESSION['username'] = $username;
            header("Location: ../feed.php");
			exit;
		} else {
			echo "Erro: " . $sql . "<br>" . mysqli_error($c);
		}
	} else {
		echo "Nome de usuário já existe";
	}

} else {
	header("Location: ./cadastro.php");
	exit;
}

mysqli_close($c);

?>
