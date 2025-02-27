<?php
// filepath: /c:/Users/rafae/Downloads/purple-tree.backend/php/post/view_post.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require '../../../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "ID do post não fornecido"]);
    exit;
}

$stmt = $c->prepare("SELECT id, conteudo, username FROM Post WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
    echo json_encode($post);
} else {
    echo json_encode(["error" => "Post não encontrado"]);
}

$stmt->close();
$c->close();
?>
