<?php
require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $horas = $_POST['tempo_estimado_horas'];

    if(trim($descricao) !== '' && is_numeric($horas) && $horas >= 0) {
        $query = "INSERT INTO prioridades (descricao, tempo_estimado_horas) VALUES (?, ?)";
        $statement = $pdo->prepare($query);
        $statement->execute([$descricao, $horas]);
        echo "<script>alert('Prioridade cadastrada!'); window.location.href='prioridades.php';</script>";
    } else {
        echo "<script>alert('Por favor, preencha a descrição e insira um número válido para as horas.');</script>";
    }
    
}

$queryPrioridade = "SELECT * FROM prioridades ORDER BY tempo_estimado_horas ASC";
$statementPrioridade = $pdo->prepare($queryPrioridade);
$statementPrioridade->execute();
$prioridades = $statementPrioridade->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Prioridades - W5i</title>
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
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">CADASTRO DE PRIORIDADE E TEMPO LIMITE</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Descrição</label>
                                <input type="text" name="descricao" class="form-control" placeholder="Ex: Alta, Crítica" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Horas Estimadas</label>
                                <input type="number" name="tempo_estimado_horas" class="form-control" placeholder="Ex: 4" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-warning w-100">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Tempo Estimado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($prioridades as $prioridade): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($prioridade['descricao']) ?></td>
                                        <td><?= $prioridade['tempo_estimado_horas'] ?> hora(s)</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="index.php" class="text-secondary text-decoration-none">← Voltar para chamados</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>