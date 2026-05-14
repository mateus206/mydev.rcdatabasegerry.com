<?php
session_start();
require_once "db.php";

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Método inválido."
    ]);
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Email e password são obrigatórios."
    ]);
    exit;
}

try {
    $sql = "SELECT id, nome, email, password FROM utilizadores WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $utilizador = $stmt->fetch();

    if (!$utilizador) {
        echo json_encode([
            "success" => false,
            "message" => "Utilizador não encontrado."
        ]);
        exit;
    }

    if ($password !== $utilizador["password"]) {
        echo json_encode([
            "success" => false,
            "message" => "Password incorrecta."
        ]);
        exit;
    }

    $_SESSION["utilizador"] = [
        "id" => $utilizador["id"],
        "nome" => $utilizador["nome"],
        "email" => $utilizador["email"]
    ];

    echo json_encode([
        "success" => true,
        "message" => "Login efectuado com sucesso.",
        "utilizador" => $_SESSION["utilizador"]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro no servidor: " . $e->getMessage()
    ]);
}
