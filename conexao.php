<?php

$servername = 'localhost';
$username = 'root';
$senha = "";
$db = "aw2-2";
$erro = null;
try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $senha);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
        $erro = $ex -> getMessage();
        echo $erro;
}
