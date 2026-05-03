<?php

require_once dirname(__DIR__) . '/config/database.php';


$id = $_GET['id'];

if(!$id){
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM chamados WHERE id = ?');
$statement->execute([$id]);
$chamado = $statement->fetch();

if(!$chamado || $chamado['status'] !== 'Em Atendimento'){
    header('Location: index.php?msg=error_status');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'checkout') {
    try {
        $solucao = $_POST['solucao'];
        
        $sql = "UPDATE chamados SET status = 'Finalizado', data_fim = NOW(), solucao = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$solucao, $id]);

        echo "<script>alert('Chamado finalizado com sucesso!'); window.location.href='index.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Erro ao finalizar: " . $e->getMessage() . "');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Chamado - W5i</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">W5i Suporte Interno</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0 h5">Finalizar Atendimento</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Você está finalizando o chamado: <br>
                        <strong>#<?= $id ?> - <?= htmlspecialchars($chamado['titulo']) ?></strong></p>

                        <form action="finalizar.php?id=<?= $id ?>" method="POST">
                            <input type="hidden" name="acao" value="checkout">
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="mb-3">
                                <label for="solucao" class="form-label font-weight-bold">Descrição da Solução</label>
                                <textarea class="form-control" id="solucao" name="solucao" rows="5"
                                    placeholder="Descreva detalhadamente o que foi feito para resolver este chamado..." required></textarea>
                                <div class="form-text">Este campo é obrigatório para o encerramento.</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="index.php" class="text-decoration-none text-secondary">Voltar</a>
                                <button type="submit" class="btn btn-success px-4">Concluir e Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>