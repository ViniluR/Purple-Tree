<?php
require '../../../db.php';

// Configuração de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Responde a requisição OPTIONS (pré-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Obtendo os dados da requisição POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os dados foram enviados corretamente
if (!isset($data["id"]) || empty($data["id"])) {
    echo json_encode(["error" => "Nenhum Post especificado"]);
    exit();
}

$id = trim($data["id"]);

// Prepara a consulta
$stmt = $c->prepare("DELETE FROM Post WHERE id = ?");
$stmt->bind_param("i", $id); // Alterado para "i" para indicar que o parâmetro é um inteiro
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => "Post excluído com sucesso"]);
} else {
    echo json_encode(["error" => "Post não encontrado ou erro ao excluir"]);
}

// Fecha a conexão
$stmt->close();
$c->close();
exit(); // Garante que não haja saída extra
?>
