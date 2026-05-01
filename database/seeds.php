<?php

require_once '/../config/database.php';

try{
    $setores = ['Marketing', 'Financeiro', 'Recursos Humanos', 'TI'];
    $statementSetor = $pdo->prepare('INSERT INTO setores (nome) VALUES (?)');

    foreach ($setores as $setor) {
        $statementSetor->execute([$setor]);
    }

    $prioridades = ['Alta', 'Média', 'Baixa'];
    $statementPrioridade = $pdo->prepare('INSERT INTO prioridades (nome) VALUES (?)');

    foreach ($prioridades as $prioridade) {
        $statementPrioridade->execute([$prioridade]);
    }

    echo 'Setores e prioridades inseridos com sucesso!';


}catch(PDOException $e){
    echo 'Erro ao tentar inserir os setores: ' . $e->getMessage();
}