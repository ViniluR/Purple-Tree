<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Verifica se os campos foram enviados corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['username'], $_POST['nome'], $_POST['biografia'])) {
        echo json_encode(["error" => "Campos incompletos"]);
        exit();
    }

    $username = $_POST['username'];
    $nome = $_POST['nome'];
    $biografia = $_POST['biografia'];

    $stmt = $c->prepare("UPDATE Usuario SET nome = ?, biografia = ? WHERE username = ?");
    if (!$stmt) {
        echo json_encode(["error" => "Erro na preparação da query: " . $c->error]);
        exit();
    }

    $stmt->bind_param("sss", $nome, $biografia, $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Usuário atualizado com sucesso!"]);
    } else {
        echo json_encode(["error" => "Erro ao atualizar usuário: " . $stmt->error]);
    }

    $stmt->close();
    $c->close();
    exit();
}
?>