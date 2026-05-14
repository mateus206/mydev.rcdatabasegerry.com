<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

if (isset($_SESSION["utilizador"])) {
    echo json_encode([
        "success" => true,
        "utilizador" => $_SESSION["utilizador"]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Sessão não iniciada."
    ]);
}
