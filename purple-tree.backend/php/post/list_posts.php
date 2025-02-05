<?php
require '../../../db.php';

// Configuração de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Responde a requisição OPTIONS (pré-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

$result = $c->query("SELECT id_postagem, titulo FROM Postagem");

$postagens = [];
while ($row = $result->fetch_assoc()) {
    $postagens[] = $row;
}

echo json_encode($postagens);

$c->close();
?>