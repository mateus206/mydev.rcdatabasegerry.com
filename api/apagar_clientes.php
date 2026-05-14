<?php
session_start();
require_once "db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["utilizador"])) {
    echo json_encode([
        "success" => false,
        "message" => "Acesso não autorizado."
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Método inválido."
    ]);
    exit;
}

$id = trim($_POST["id"] ?? "");

if ($id === "") {
    echo json_encode([
        "success" => false,
        "message" => "ID do cliente é obrigatório."
    ]);
    exit;
}

try {
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode([
        "success" => true,
        "message" => "Cliente apagado com sucesso."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao apagar cliente: " . $e->getMessage()
    ]);
}
