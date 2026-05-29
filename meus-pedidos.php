<?php
session_start();
require_once 'config/crud.php';

 
if (!isset($_SESSION['id_login']) || $_SESSION['papel'] !== 'cliente') {
    header('Location: login.php');
    exit;
}
 
$id = $_SESSION['id_login'];
$pedidos = readAll($pdo, 'pedidos', "id_login = $id ORDER BY data_pedido DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
       
    </style>
</head>
<body>
<?php include 'partials/header.php'; ?>
<main>
    <div class="pedidos-wrapper">
        <h1 class="pedidos-titulo">Meus Pedidos</h1>
 
        <?php if (empty($pedidos)): ?>
            <div class="pedidos-vazios">
                <p>Você ainda não realizou nenhum pedido.</p>
                <a href="produtos.php" class="btn" style="width:auto; display:inline-block;">Ver produtos</a>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $ped):
                $statusClass = match(strtolower($ped['status'])) {
                    'pendente'    => 'badge-pendente',
                    'processando' => 'badge-processando',
                    'enviado'     => 'badge-enviado',
                    'entregue'    => 'badge-entregue',
                    'cancelado'   => 'badge-cancelado',
                    default       => 'badge-pendente'
                };
                $itens = readAll($pdo, 'itens_pedido', "id_pedido = {$ped['id_pedido']}");
            ?>
            <div class="pedido-card">
                <div class="pedido-header">
                    <div class="pedido-header-left">
                        <span class="pedido-id">Pedido #<?= $ped['id_pedido'] ?></span>
                        <span class="pedido-data"><?= date('d/m/Y \à\s H:i', strtotime($ped['data_pedido'])) ?></span>
                        <span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($ped['status']) ?></span>
                    </div>
                    <span class="pedido-total">Total: R$ <?= number_format($ped['total'], 2, ',', '.') ?></span>
                </div>
 
                <div class="pedido-itens">
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th style="text-align:center">Qtd.</th>
                                <th style="text-align:right">Unitário</th>
                                <th style="text-align:right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item):
                                $prod = read($pdo, 'produtos', "id = {$item['id_produto']}");
                                $subtotal = $item['qtd_comprada'] * $item['preco_unitario'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($prod['item']) ?></td>
                                <td style="text-align:center"><?= $item['qtd_comprada'] ?></td>
                                <td style="text-align:right">R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                <td style="text-align:right; font-weight:600; color:#E0E0E0">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>