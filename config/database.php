<?php

date_default_timezone_set('America/Bahia');

$host = '127.0.0.1'; #IP do servidor de banco de dados
$db = 'w5i_chamados'; #Nome do banco de dados
$username = 'root'; #Usuário do banco de dados (geralmente 'root' para desenvolvimento local)
$password = '0515'; #Senha do banco de dados
$charset = 'utf8mb4';

$dataSourceName = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];


try{
    $pdo = new PDO($dataSourceName, $username, $password, $options);
}catch(PDOException $e){
    echo 'Erro na conexão: ' . $e->getMessage();
}