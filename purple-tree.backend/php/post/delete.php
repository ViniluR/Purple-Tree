<?php
require '../../../db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id_postagem = $data['id_postagem'] ?? null;

if ($id_postagem) {
    $stmt = $c->prepare("DELETE FROM Postagem WHERE id_postagem = ?");
    $stmt->bind_param("i", $id_postagem);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "Postagem excluída com sucesso."]);
    } else {
        echo json_encode(["error" => "Erro ao excluir postagem."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID inválido."]);
}

$c->close();
?>