<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
    <main class='login-cadastro'>
        <form class='login-cadastro' action="back/login.php" method="POST">
            <div style='position: relative'>
                <a id='voltar' href='index.php'><img height='20'src='media/voltar.png'></a>
                <h2>Login</h2>
            </div>
            <label for="username">Nome de usuário</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="senha">Senha</label><br>
            <input type="password" id="senha" name="senha" required><br><br>

            <input style='cursor:pointer'type="submit" class='cadastro-login-action' value="Entrar"><br>
        </form>
        <a class='ja-ainda' href="cadastro.php">Ainda não possui uma conta?</a>
    </main>
</body>
</html>
