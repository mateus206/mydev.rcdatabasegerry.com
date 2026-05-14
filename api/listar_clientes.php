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

try {
    $sql = "SELECT id, nome, nif, email, telefone, morada FROM clientes ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $clientes = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "clientes" => $clientes
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao listar clientes: " . $e->getMessage()
    ]);
}
