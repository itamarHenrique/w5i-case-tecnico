<?php
require_once '/../config/database.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

if (!$acao){
    header('Location: ../../public/index.php');
    exit;
}

try{
    switch($acao){
        case 'cadastrar':
            cadastrarChamado($pdo);
            break;
        
        case 'checkin':
            realizarCheckin($pdo);
            break;
        
        case 'checkout':
            realizarCheckout($pdo);
            break;
        
        default:
            header('Location: ../../public/index.php');
            exit;
    }
}catch(PDOException $e){
    echo 'Erro na solicitação: ' . $e->getMessage();
    exit;
}

function cadastrarChamado($pdo){
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $setor_id = $_POST['setor_id'];
    $prioridade_id = $_POST['prioridade_id'];

    $statementCadastro = $pdo->prepare("INSERT INTO chamados (titulo, descricao, setor_id, prioridade_id) VALUES (?, ?, ?, ?)");
    $statementCadastro->execute([$titulo, $descricao, $setor_id, $prioridade_id]);

    header("Location: ../../public/index.php?msg=cadastrado");
}

function realizarCheckin($pdo){
    $id = $_POST['id'];
    
    $statementCheckin = $pdo->prepare("SELECT status FROM chamados WHERE id = ?");
    $statementCheckin->execute([$id]);
    $chamado = $statementCheckin->fetch();

    if($chamado['status'] !== 'aberto'){
            throw new Exception ('Apenas chamados abertos podem ser iniciados.');
    }

    $sqlCheckin = "UPDATE chamados SET status = 'em andamento', data_inicio = NOW() where id = ?";
    $statementCheckin = $pdo->prepare($sqlCheckin);
    $statementCheckin->execute([$id]);

    header("Location: ../../public/index.php?msg=iniciado");
}

function realizarCheckout($pdo){
    $id = $_POST['id'];
    $solucao = $_POST['solucao'];

    $statementCheckout = $pdo->prepare("SELECT status FROM chamados WHERE id = ?");
    $statementCheckout->execute([$id]);
    $chamado = $statementCheckout->fetch();

    if(!$chamado['data_inicio'] || $chamado['status'] !== 'em andamento'){
        throw new Exception ('Apenas chamados em andamento podem ser finalizados.');
    }

    $sqlCheckout = "UPDATE chamados SET status = 'finalizado', data_fim = NOW(), solucao = ? WHERE id = ? ";
    $statementCheckout = $pdo->prepare($sqlCheckout);
    $statementCheckout->execute([$solucao, $id]);

    header("Location: ../../public/index.php?msg=finalizado");
}