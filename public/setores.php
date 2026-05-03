<?php
require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    if(trim($nome) !== '') {
        $query = "INSERT INTO setores (nome) VALUES (?)";
        $statement = $pdo->prepare($query);
        $statement->execute([$nome]);
        echo "<script>alert('Setor cadastrado!'); window.location.href='setores.php';</script>";
    } else {
        echo "<script>alert('O nome do setor não pode ser vazio.');</script>";
    }
    
}

$querySetor = "SELECT * FROM setores ORDER BY nome ASC";
$statementSetor = $pdo->prepare($querySetor);
$statementSetor->execute();
$setores = $statementSetor->fetchAll();


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Setores - W5i</title>
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">CADASTRO DE SETOR</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <div class="col-auto flex-grow-1">
                                <input type="text" name="nome" class="form-control" placeholder="Ex: Financeiro, TI, RH" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Salvar Setor</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="100">ID</th>
                                    <th>Nome do Setor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($setores as $setor): ?>
                                    <tr>
                                        <td>#<?= $setor['id'] ?></td>
                                        <td><?= htmlspecialchars($setor['nome']) ?></td>
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