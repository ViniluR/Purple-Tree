<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro</title>
	<link rel="stylesheet" href="styles.css">
	<script src="script.js"></script>
</head>
<body>
	<main class='login-cadastro'>
		<form class='login-cadastro'action="back/cadastro.php" method="POST">
			<div style='position: relative'>
                <a id='voltar' href='feed.php'><img height='20' src='media/voltar.png'></a>
                <h2>Cadastro</h2>
            </div>
			<label for="nome">Nome</label><br>
			<input type="text" id="nome" name="nome" required><br><br>

			<label for="username">Nome de usuário</label><br>
			<input type="text" id="username" name="username" required><br><br>

			<label for="senha">Senha</label><br>
			<input type="password" id="senha" name="senha" required><br><br>

			<label for="biografia">Biografia</label><br>
			<textarea required maxlength='500' id="biografia" name='biografia'></textarea><br><br>

			<input style='cursor:pointer' type="submit" class='cadastro-login-action' value="Cadastrar">
		</form>
		<a class='ja-ainda' href="login.php">Já possui uma conta?</a>
	</main>
</body>
</html>
