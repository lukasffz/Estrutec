<?php
require_once 'config/crud.php';
session_start();
 
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
        .pedidos-wrapper {
            max-width: 900px;
            margin: 2.5rem auto;
            padding: 0 2rem;
        }
 
        .pedidos-titulo {
            font-size: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #4A90D9;
            padding-left: 1rem;
        }
 
        /* Card de cada pedido */
        .pedido-card {
            background: #1F2C45;
            border-radius: 16px;
            border: 1px solid #2e4266;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: border-color 0.2s;
        }
 
        .pedido-card:hover {
            border-color: #4A90D9;
        }
 
        /* Cabeçalho do card */
        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #1B2B44;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
 
        .pedido-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
 
        .pedido-id {
            font-weight: 700;
            color: #4A90D9;
            font-size: 1rem;
        }
 
        .pedido-data {
            font-size: 0.85rem;
            color: #A0B0C8;
        }
 
        /* Badge de status */
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
 
        .badge-pendente    { background: rgba(255, 193, 7,  0.15); color: #FFC107; border: 1px solid rgba(255,193,7,0.3); }
        .badge-processando { background: rgba(74, 144, 217, 0.15); color: #4A90D9; border: 1px solid rgba(74,144,217,0.3); }
        .badge-enviado     { background: rgba(22, 178, 212, 0.15); color: #16B2D4; border: 1px solid rgba(22,178,212,0.3); }
        .badge-entregue    { background: rgba(40, 200, 100, 0.15); color: #28C864; border: 1px solid rgba(40,200,100,0.3); }
        .badge-cancelado   { background: rgba(220,  60,  60, 0.15); color: #DC3C3C; border: 1px solid rgba(220,60,60,0.3); }
 
        .pedido-total {
            font-weight: 700;
            color: #E0E0E0;
            font-size: 1rem;
            white-space: nowrap;
        }
 
        /* Lista de itens */
        .pedido-itens {
            padding: 1rem 1.5rem;
        }
 
        .pedido-itens table {
            width: 100%;
            border-collapse: collapse;
        }
 
        .pedido-itens th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #A0B0C8;
            padding: 6px 8px;
            border-bottom: 1px solid #2e4266;
            text-align: left;
            background: transparent;
        }
 
        .pedido-itens td {
            padding: 10px 8px;
            border-bottom: 1px solid #1B2B44;
            color: #C0CDE0;
            font-size: 0.9rem;
        }
 
        .pedido-itens tbody tr:last-child td {
            border-bottom: none;
        }
 
        .pedido-itens tbody tr:hover {
            background: rgba(255,255,255,0.03);
        }
 
        /* Estado vazio */
        .pedidos-vazios {
            text-align: center;
            padding: 4rem 2rem;
            color: #A0B0C8;
        }
 
        .pedidos-vazios p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
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