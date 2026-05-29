<?php
require_once '../config/crud.php';
// REMOVIDO: $requer_gerente = true; (Pois funcionário também entra aqui)
include '../config/auth.php';

$papel_usuario = $_SESSION['papel'];

// Dados que AMBOS (Funcionário e Gerente) podem ver
$criticos = count(readAll($pdo, 'produtos', "quantidade < 10"));
$pendentes = count(readAll($pdo, 'pedidos', "status = 'Pendente'"));

// Dados exclusivos do GERENTE (só puxa do banco se for gerente)
if ($papel_usuario === 'gerente') {
    $faturamento = readAll($pdo, 'pedidos', "status = 'Concluído'");
    $totalFat = array_sum(array_column($faturamento, 'total'));
    $clientes = count(readAll($pdo, 'cadastrados', "papel = 'cliente' AND ativo = 1"));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Estrutec</title>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">
    <div class="dashboard-cards">
        
        <?php if ($papel_usuario === 'gerente'): ?>
            <div class="card">
                <h3>Vendas do Mês</h3>
                <div class="valor">R$ <?= number_format($totalFat, 2, ',', '.') ?></div>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>Estoque baixo</h3>
            <div class="valor"><?= $criticos ?></div>
        </div>

        <?php if ($papel_usuario === 'gerente'): ?>
            <div class="card">
                <h3>Clientes Ativos</h3>
                <div class="valor"><?= $clientes ?></div>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>Pedidos Pendentes</h3>
            <div class="valor"><?= $pendentes ?></div>
        </div>

    </div>
</div>
</body>
</html>