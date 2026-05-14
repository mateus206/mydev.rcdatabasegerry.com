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

$id       = trim($_POST["id"] ?? "");
$nome     = trim($_POST["nome"] ?? "");
$nif      = trim($_POST["nif"] ?? "");
$email    = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$morada   = trim($_POST["morada"] ?? "");

if ($id === "" || $nome === "" || $nif === "") {
    echo json_encode([
        "success" => false,
        "message" => "Os campos ID, Nome e NIF são obrigatórios."
    ]);
    exit;
}

try {
    $sql = "UPDATE clientes SET nome = ?, nif = ?, email = ?, telefone = ?, morada = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $nif, $email, $telefone, $morada, $id]);

    echo json_encode([
        "success" => true,
        "message" => "Cliente editado com sucesso."
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao editar cliente: " . $e->getMessage()
    ]);
}
