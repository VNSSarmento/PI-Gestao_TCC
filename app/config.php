<?php

$host = 'localhost';
$db   = 'pi';
$user = 'root';
$pass = ''; // ou sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

;
