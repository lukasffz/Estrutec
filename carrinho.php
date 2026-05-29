<?php
require_once 'config/crud.php';
session_start();
 
if (!isset($_SESSION['id_login']) || $_SESSION['papel'] !== 'cliente') {
    header('Location: login.php');
    exit;
}
 
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['carrinho'][$id] = ($_SESSION['carrinho'][$id] ?? 0) + 1;
    header('Location: carrinho.php');
    exit;
}
 
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header('Location: carrinho.php');
    exit;
}
 
if (isset($_POST['atualizar'])) {
    foreach ($_POST['qtd'] as $id => $qtd) {
        if ($qtd <= 0) unset($_SESSION['carrinho'][$id]);
        else $_SESSION['carrinho'][$id] = (int)$qtd;
    }
    header('Location: carrinho.php');
    exit;
}
 
$itensCarrinho = [];
$totalGeral = 0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $id => $qtd) {
        $produto = read($pdo, 'produtos', "id = $id");
        if ($produto) {
            $subtotal = $produto['preco'] * $qtd;
            $totalGeral += $subtotal;
            $itensCarrinho[] = [
                'id'       => $id,
                'nome'     => $produto['item'],
                'imagem'   => $produto['imagem'] ?: 'imagens/produto-padrao.jpg',
                'preco'    => $produto['preco'],
                'qtd'      => $qtd,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .carrinho-wrapper {
            max-width: 1100px;
            margin: 2.5rem auto;
            padding: 0 2rem;
        }
 
        .carrinho-titulo {
            font-size: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #4A90D9;
            padding-left: 1rem;
        }
 
        /* Layout principal: tabela + resumo lado a lado */
        .carrinho-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 2rem;
            align-items: start;
        }
 
        /* Tabela */
        .carrinho-tabela-wrap {
            background: #1F2C45;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #2e4266;
        }
 
        .carrinho-tabela-wrap table {
            width: 100%;
            border-collapse: collapse;
        }
 
        .carrinho-tabela-wrap th {
            background: #1B2B44;
            color: #A0B0C8;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            font-weight: 600;
        }
 
        .carrinho-tabela-wrap td {
            padding: 16px;
            border-bottom: 1px solid #2e4266;
            vertical-align: middle;
            color: #E0E0E0;
            font-size: 0.95rem;
        }
 
        .carrinho-tabela-wrap tbody tr:last-child td {
            border-bottom: none;
        }
 
        .carrinho-tabela-wrap tbody tr:hover {
            background: #1a2a42;
        }
 
        /* Célula de produto com imagem */
        .prod-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }
 
        .prod-cell img {
            width: 58px;
            height: 58px;
            object-fit: cover;
            border-radius: 10px;
            background: #28395A;
            border: 1px solid #2e4266;
            flex-shrink: 0;
        }
 
        .prod-nome {
            font-weight: 600;
            color: #E0E0E0;
            font-size: 0.95rem;
        }
 
        /* Controle de quantidade */
        .qty-ctrl {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #28395A;
            border: 1px solid #2e4266;
            border-radius: 30px;
            padding: 4px 10px;
        }
 
        .qty-btn {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: none;
            background: #1F2C45;
            color: #E0E0E0;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            line-height: 1;
        }
 
        .qty-btn:hover {
            background: #4A90D9;
            color: #fff;
        }
 
        .qty-input {
            width: 36px;
            text-align: center;
            background: transparent;
            border: none;
            color: #E0E0E0;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: inherit;
            outline: none;
        }
 
        /* Botão remover */
        .btn-remover {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
        }
 
        .btn-remover img {
            width: 18px;
            opacity: 0.5;
            filter: invert(1);
            transition: opacity 0.2s;
        }
 
        .btn-remover:hover {
            background: rgba(220, 60, 60, 0.15);
        }
 
        .btn-remover:hover img {
            opacity: 1;
            filter: invert(30%) sepia(100%) saturate(500%) hue-rotate(320deg);
        }
 
        /* Caixa de resumo */
        .resumo-box {
            background: #1F2C45;
            border-radius: 16px;
            border: 1px solid #2e4266;
            padding: 1.5rem;
            position: sticky;
            top: 100px;
        }
 
        .resumo-box h2 {
            font-size: 1.1rem;
            color: #E0E0E0;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #2e4266;
        }
 
        .resumo-linha {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #A0B0C8;
            margin-bottom: 0.75rem;
        }
 
        .resumo-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.15rem;
            font-weight: 700;
            color: #E0E0E0;
            padding-top: 0.75rem;
            border-top: 1px solid #2e4266;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }
 
        .resumo-total span:last-child {
            color: #4A90D9;
        }
 
        .btn-finalizar {
            display: block;
            width: 100%;
            padding: 13px;
            text-align: center;
            background: #16B2D4;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
            margin-bottom: 0.75rem;
            font-family: inherit;
        }
 
        .btn-finalizar:hover {
            opacity: 0.88;
            transform: translateY(-2px);
        }
 
        .btn-atualizar {
            display: block;
            width: 100%;
            padding: 11px;
            text-align: center;
            background: transparent;
            color: #C0D0E8;
            border: 1px solid #2e4266;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
            font-family: inherit;
            margin-bottom: 0.75rem;
        }
 
        .btn-atualizar:hover {
            border-color: #4A90D9;
            color: #4A90D9;
        }
 
        .btn-continuar {
            display: block;
            text-align: center;
            font-size: 0.85rem;
            color: #A0B0C8;
            text-decoration: none;
            transition: color 0.2s;
        }
 
        .btn-continuar:hover {
            color: #4A90D9;
        }
 
        /* Carrinho vazio */
        .carrinho-vazio {
            text-align: center;
            padding: 4rem 2rem;
            color: #A0B0C8;
        }
 
        .carrinho-vazio p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
 
        /* Responsivo */
        @media (max-width: 820px) {
            .carrinho-layout {
                grid-template-columns: 1fr;
            }
            .resumo-box {
                position: static;
            }
        }
    </style>
</head>
<body>
<?php include 'partials/header.php'; ?>
<main>
    <div class="carrinho-wrapper">
        <h1 class="carrinho-titulo">Meu Carrinho</h1>
 
        <?php if (empty($itensCarrinho)): ?>
            <div class="carrinho-vazio">
                <p>Seu carrinho está vazio.</p>
                <a href="produtos.php" class="btn" style="width:auto; display:inline-block;">Ver produtos</a>
            </div>
        <?php else: ?>
            <form method="POST" id="form-carrinho">
                <div class="carrinho-layout">
 
                    <!-- Tabela de itens -->
                    <div class="carrinho-tabela-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itensCarrinho as $item): ?>
                                <tr>
                                    <td>
                                        <div class="prod-cell">
                                            <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                            <span class="prod-nome"><?= htmlspecialchars($item['nome']) ?></span>
                                        </div>
                                    </td>
                                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                    <td>
                                        <div class="qty-ctrl">
                                            <button type="button" class="qty-btn" onclick="ajustarQtd(this, -1)">−</button>
                                            <input
                                                type="number"
                                                class="qty-input"
                                                name="qtd[<?= $item['id'] ?>]"
                                                value="<?= $item['qtd'] ?>"
                                                min="0"
                                                data-preco="<?= $item['preco'] ?>"
                                                onchange="recalcular()"
                                            >
                                            <button type="button" class="qty-btn" onclick="ajustarQtd(this, 1)">+</button>
                                        </div>
                                    </td>
                                    <td class="subtotal-cell">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                    <td>
                                        <a href="carrinho.php?remove=<?= $item['id'] ?>" class="btn-remover" title="Remover item">
                                            <img src="imagens/delete.png" alt="Remover">
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
 
                    <!-- Resumo / checkout -->
                    <div class="resumo-box">
                        <h2>Resumo do pedido</h2>
                        <div class="resumo-linha">
                            <span><?= count($itensCarrinho) ?> ite<?= count($itensCarrinho) > 1 ? 'ns' : 'm' ?></span>
                            <span id="resumo-subtotal">R$ <?= number_format($totalGeral, 2, ',', '.') ?></span>
                        </div>
                        <div class="resumo-linha">
                            <span>Frete</span>
                            <span style="color:#4A90D9">A calcular</span>
                        </div>
                        <div class="resumo-total">
                            <span>Total</span>
                            <span id="resumo-total">R$ <?= number_format($totalGeral, 2, ',', '.') ?></span>
                        </div>
 
                        <button type="button" onclick="document.getElementById('form-finalizar').submit()" class="btn-finalizar">
                            Finalizar Pedido
                        </button>
                        <a href="produtos.php" class="btn-continuar">← Continuar comprando</a>
                    </div>
 
                </div>
            </form>
 
            <!-- Form separado para finalizar -->
            <form id="form-finalizar" action="crud/finalizar-pedido.php" method="POST" style="display:none;"></form>
        <?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
 
<script>
    function ajustarQtd(btn, delta) {
        const input = btn.parentElement.querySelector('.qty-input');
        const novoVal = Math.max(0, parseInt(input.value || 0) + delta);
        input.value = novoVal;
        recalcular();
    }
 
    function recalcular() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach((input, i) => {
            const qtd = parseInt(input.value) || 0;
            const preco = parseFloat(input.dataset.preco);
            const subtotal = qtd * preco;
            total += subtotal;
            const cells = document.querySelectorAll('.subtotal-cell');
            if (cells[i]) {
                cells[i].textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });
        const fmt = total.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        document.getElementById('resumo-subtotal').textContent = 'R$ ' + fmt;
        document.getElementById('resumo-total').textContent = 'R$ ' + fmt;
    }
</script>
</body>
</html>