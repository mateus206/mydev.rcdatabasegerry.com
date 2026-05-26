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

$nome = trim($_POST["nome"] ?? "");
$nif = trim($_POST["nif"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$morada = trim($_POST["morada"] ?? "");

if ($nome === "" || $nif === "") {
    echo json_encode([
        "success" => false,
        "message" => "Os campos Nome e NIF são obrigatórios."
    ]);
    exit;
}

try {
    $sql = "INSERT INTO clientes (nome, nif, email, telefone, morada) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $nif, $email, $telefone, $morada]);

    echo json_encode([
        "success" => true,
        "message" => "Cliente criado com sucesso."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao criar cliente: " . $e->getMessage()
    ]);
}
