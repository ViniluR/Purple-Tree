<?php
require '../../../db.php';

// Configuração de CORS
header("Access-Control-Allow-Origin: http://purple-tree.biz");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Responde a requisição OPTIONS (pré-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Verifica se o username foi enviado
if (!isset($_GET["username"]) || empty($_GET["username"])) {
    echo json_encode(["error" => "Nenhum usuário especificado"]);
    exit();
}

$username = trim($_GET["username"]);

// Prepara a consulta
$stmt = $c->prepare("SELECT username, nome, biografia FROM Usuario WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Retorna os dados em JSON
if ($usuario) {
    echo json_encode($usuario);
} else {
    echo json_encode(["error" => "Usuário não encontrado"]);
}

// Fecha a conexão
$stmt->close();
$c->close();
?>