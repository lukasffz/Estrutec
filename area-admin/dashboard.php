<?php
require_once '../config/crud.php';
include '../config/auth.php';

$papel_usuario = $_SESSION['papel'];

$id_usuario_logado = $_SESSION['id_login'] ?? $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? 0;
$usuario_db = read($pdo, 'cadastrados', "id_login = $id_usuario_logado");
$nome_usuario = $usuario_db['nome'] ?? $_SESSION['nome'] ?? 'Administrador';

/*dados para o dashboard*/
$criticos = count(readAll($pdo, 'produtos', "quantidade < 30"));
$pendentes = count(readAll($pdo, 'pedidos', "status = 'Pendente'"));

if ($papel_usuario === 'gerente') {
    $faturamento = readAll($pdo, 'pedidos', "status = 'Concluído'");
    $totalFat = array_sum(array_column($faturamento, 'total'));
    $clientes = count(readAll($pdo, 'cadastrados', "papel = 'cliente' AND ativo = 1"));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Estrutec</title>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">

    <div class="welcome-glass-card">
        <div class="welcome-info">
            <h2>Olá, <span class="user-highlight"><?= htmlspecialchars($nome_usuario) ?></span>!</h2>
            <p>Seja bem-vindo ao painel de controle.</p>
        </div>
        <div class="welcome-meta">
            <span class="user-role-badge"><?= ucfirst($papel_usuario) ?></span>
        </div>
    </div>

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