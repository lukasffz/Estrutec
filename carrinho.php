<?php
require_once 'config/crud.php';
session_start();
 
if (!isset($_SESSION['id_login']) || $_SESSION['papel'] !== 'cliente') {
    header('Location: login.php');
    exit;
}
 
// Incrementar quantidade (+1 via link GET)
if (isset($_GET['inc'])) {
    $id = (int)$_GET['inc'];
    $_SESSION['carrinho'][$id] = ($_SESSION['carrinho'][$id] ?? 0) + 1;
    header('Location: carrinho.php');
    exit;
}
 
// Decrementar quantidade (-1 via link GET, remove se chegar a 0)
if (isset($_GET['dec'])) {
    $id = (int)$_GET['dec'];
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]--;
        if ($_SESSION['carrinho'][$id] <= 0) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
    header('Location: carrinho.php');
    exit;
}
 
// Adicionar ao carrinho (vindo de produtos.php)
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['carrinho'][$id] = ($_SESSION['carrinho'][$id] ?? 0) + 1;
    header('Location: carrinho.php');
    exit;
}
 
// Remover item inteiro
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header('Location: carrinho.php');
    exit;
}
 
// Atualizar quantidades via POST (digitação direta no input)
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

    <!-- ADICIONA AQUI -->
    <script>
        function confirmarPedido(total) {
            return confirm(
                'Confirmar pedido?\n\n' +
                'Total: R$ ' + total + '\n\n' +
                'Clique em OK para finalizar.'
            );
        }
    </script>

</head>

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
                                    <!-- Botões +/- são links GET — sem JS -->
                                    <div class="qty-ctrl">
                                        <a href="carrinho.php?dec=<?= $item['id'] ?>" class="qty-btn" title="Diminuir">−</a>
                                        <span class="qty-valor"><?= $item['qtd'] ?></span>
                                        <a href="carrinho.php?inc=<?= $item['id'] ?>" class="qty-btn" title="Aumentar">+</a>
                                    </div>
                                </td>
                                <td style="font-weight:600; color:#E0E0E0">
                                    R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                                </td>
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
                        <span>R$ <?= number_format($totalGeral, 2, ',', '.') ?></span>
                    </div>
                    <div class="resumo-linha">
                        <span>Frete</span>
                        <span style="color:#4A90D9">A calcular</span>
                    </div>
                    <div class="resumo-total">
                        <span>Total</span>
                        <span>R$ <?= number_format($totalGeral, 2, ',', '.') ?></span>
                    </div>
 
                    <!-- Finalizar: link direto para o script PHP -->
                    <a href="crud/finalizar-pedido.php" 
                        class="btn-finalizar"
                        onclick="return confirmarPedido('<?= number_format($totalGeral, 2, ',', '.') ?>')">
                        Finalizar Pedido
                    </a>
                    <a href="produtos.php" class="btn-continuar">← Continuar comprando</a>
                </div>
 
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>