<?php

$host = "localhost";
$user = "root"; 
$password = "";
$database = "cadastro"; 

try {
    $conexao = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conexao; 
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
