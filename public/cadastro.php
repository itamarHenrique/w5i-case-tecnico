<?php

require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    try {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $setor_id = $_POST['setor_id'];
        $prioridade_id = $_POST['prioridade_id'];

        if(trim($titulo) !== '' && trim($descricao) !== '' && is_numeric($setor_id) && is_numeric($prioridade_id)) {
            $query = "INSERT INTO chamados (titulo, descricao, setor_id, prioridade_id, status) VALUES (?, ?, ?, ?, 'Aberto')";
            $statement = $pdo->prepare($query);
            $statement->execute([$titulo, $descricao, $setor_id, $prioridade_id]);
            echo "<script>window.location.href='index.php';</script>";
            exit;
        } else {
        echo "<script>alert('Por favor, preencha a descrição e insira um número válido para as horas.');</script>";
        }
    } catch (PDOException $e) {
        $erro = "Erro ao salvar: " . $e->getMessage();
    }
}


$setores = $pdo->query('SELECT * from setores ORDER BY nome ASC')->fetchAll();

$prioridades = $pdo->query('SELECT * FROM prioridades ORDER BY tempo_estimado_horas ASC')->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Chamado - W5i</title>
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
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0 h5">Abertura de Chamado</h3>
                    </div>
                    <div class="card-body">
                        <form action="cadastro.php" method="POST">
                            <input type="hidden" name="acao" value="cadastrar">

                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Solicitação</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex: Problema com o acesso ao e-mail" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="setor_id" class="form-label">Setor Destino</label>
                                    <select class="form-select" id="setor_id" name="setor_id" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($setores as $setor): ?>
                                            <option value="<?= $setor['id'] ?>"><?= $setor['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="prioridade_id" class="form-label">Prioridade</label>
                                    <select class="form-select" id="prioridade_id" name="prioridade_id" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($prioridades as $prioridade): ?>
                                            <option value="<?= $prioridade['id'] ?>">
                                                <?= $prioridade['descricao'] ?> (Tempo Limite: <?= $prioridade['tempo_estimado_horas'] ?>h)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição Detalhada</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Descreva aqui o problema ou solicitação..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Abrir Chamado</button>
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