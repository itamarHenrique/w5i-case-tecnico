<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once  __DIR__ . '/../src/Services/tempoService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'checkin') {
    try {
        $id = $_POST['id'];
        
        $sqlCheckin = "UPDATE chamados SET status = 'Em Atendimento', data_inicio = NOW() WHERE id = ?";
        $statementCheckin = $pdo->prepare($sqlCheckin);
        $statementCheckin->execute([$id]);

        echo "<script>window.location.href='index.php?msg=atendimento_iniciado&id=$id';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Erro ao iniciar atendimento: " . $e->getMessage() . "');</script>";
    }
}



try {
    $query = "SELECT c.*, s.nome AS setor_nome, p.descricao AS prioridade_nome, p.tempo_estimado_horas 
              FROM chamados c
              LEFT JOIN setores s ON c.setor_id = s.id
              LEFT JOIN prioridades p ON c.prioridade_id = p.id
              ORDER BY c.id DESC";

    $statement = $pdo->prepare($query);
    $statement->execute();
    $chamados = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W5i - Sistema de Chamados</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .atrasado { background-color: #fff5f5; }
        .badge-aberto { background-color: #0d6efd; }
        .badge-atendimento { background-color: #ffc107; color: #000; }
        .badge-finalizado { background-color: #198754; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <span class="navbar-brand mb-0 h1">W5i Suporte Interno</span>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="index.php">Chamados</a>
                    <a class="nav-link" href="setores.php">Setores</a>
                    <a class="nav-link" href="prioridades.php">Prioridades</a>
                </div>
                
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Gerenciamento de Chamados</h2>
            <a href="cadastro.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo Chamado
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Chamado</th>
                                <th>Setor / Prioridade</th>
                                <th>Status</th>
                                <th>Tempo de Atendimento</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chamados as $chamado):
                                $tempo = calcularTempo($chamado['data_inicio'], $chamado['data_fim'], $chamado['tempo_estimado_horas']);
                            ?>
                            <tr class="<?= $tempo['estourou'] ? 'atrasado' : '' ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        
                                        <?php if ($tempo['estourou']): ?>
                                            <span title="Prazo excedido" class="text-danger me-2">⚠️</span>
                                        <?php endif; ?>
                                        
                                        
                                        <strong class="text-dark"><?=$chamado['id']?></strong>
                                        
                                        
                                        <span class="text-muted ms-2" style="font-size: 0.9rem;">
                                            <i class="bi bi-calendar3"></i>
                                            <?= date('- d/m H:i', strtotime($chamado['data_criacao'])) ?>
                                        </span>
                                    </div>

                                    <div class="mt-1">
                                        <span class="text-dark fw-medium"><?= htmlspecialchars($chamado['titulo']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?= $chamado['setor_nome'] ?><br>
                                    <?php
                                        $horas = $chamado['tempo_estimado_horas'];
                                        if($horas <= 1){
                                            $corPrioridade = 'bg-danger';
                                        } elseif($horas < 4 ){
                                            $corPrioridade = 'bg-warning text-dark';
                                        }else{
                                            $corPrioridade = 'bg-info text-dark';
                                        }
                                        ?>
                                    <span class="badge <?= $corPrioridade ?> text-uppercase">
                                        <?= $chamado['prioridade_nome'] ?> (<?= $horas ?>h)
                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $classeStatus = match($chamado['status']) {
                                            'Aberto' => 'badge-aberto',
                                            'Em Atendimento' => 'badge-atendimento',
                                            'Finalizado' => 'badge-finalizado',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                    <span class="badge <?= $classeStatus ?>"><?= $chamado['status'] ?></span>
                                </td>
                                <td class="font-monospace <?= $tempo['estourou'] ? 'text-danger fw-bold' : '' ?>">
                                    <?= $tempo['formatado'] ?>
                                    <?php if ($tempo['estourou']): ?>
                                        <span title="Prazo excedido">⚠️</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($chamado['status'] == 'Aberto'): ?>
                                        <form action="index.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="acao" value="checkin">
                                            <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Dar Check-in</button>
                                        </form>
                                    <?php elseif ($chamado['status'] == 'Em Atendimento'): ?>
                                        <a href="finalizar.php?id=<?= $chamado['id'] ?>" class="btn btn-sm btn-outline-success">Finalizar</a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light text-muted" disabled>Concluído</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>