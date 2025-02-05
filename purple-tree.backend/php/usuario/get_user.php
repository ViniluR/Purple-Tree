<?php
require '../../../db.php';

// Configuração de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://purple-tree.biz");

// Responde a requisição OPTIONS (pré-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Verifica se o username foi enviado
if (isset($_GET["username"])) {
    $username = $_GET["username"];

    $stmt = $c->prepare("SELECT username, nome, biografia FROM Usuario WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    echo json_encode($usuario ?: ["error" => "Usuário não encontrado"]);
    
    $stmt->close();
    $c->close();
} else {
    echo json_encode(["error" => "Nenhum usuário especificado"]);
}
?>