<?php
if (!defined('GAYME')) {
    http_response_code(403);
    exit('Acesso direto nao permitido.');
}

$servername = 'localhost';
$username = 'root';
$senha = '';
$db = 'aw2_2';

$conn = null;
$erro = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8mb4", $username, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    $erro = $ex->getMessage();
    $conn = null;
}
