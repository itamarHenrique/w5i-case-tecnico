<?php

$host = '';
$db = '';
$username = '';
$password = '';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo 'Erro na conexão: ' . $e->getMessage();
}