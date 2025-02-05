<?php
require '../../../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");

$id_postagem = $_GET['id'] ?? null;
$post = null;

if ($id_postagem) {
    $stmt = $c->prepare("SELECT id_postagem, conteudo, username FROM Postagem WHERE id_postagem = ?");
    $stmt->bind_param("i", $id_postagem);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
}

$c->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/css/purple.css">
    <title>Admin - Ver Post</title>
</head>

<body>
    <header>
        <div class="header">
            <h1><a href="index.html"><img src="media/logo.png" alt="Logo"></a></h1>
        </div>
    </header>

    <main>
        <section class="PostRead">
            <h3>ID da Postagem: <?php echo htmlspecialchars($post['id_postagem'] ?? 'Não encontrado'); ?></h3>
            <div>
                <ul>
                    <li><span>Conteúdo:</span> <?php echo htmlspecialchars($post['conteudo'] ?? 'Não disponível'); ?></li>
                    <li><span>Usuário:</span> <?php echo htmlspecialchars($post['username'] ?? 'Desconhecido'); ?></li>
                </ul>
            </div>
        </section>
    </main>
</body>

</html>