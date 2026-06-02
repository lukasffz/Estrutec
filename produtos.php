<?php
require_once 'config/crud.php';
session_start(); 

$categoriaSelecionada = $_GET['categoria'] ?? '';

$where = "status = 1";
if ($categoriaSelecionada) {
    $where .= " AND categoria = '$categoriaSelecionada'";
}
$produtos = readAll($pdo, 'produtos', $where);
$todasCategorias = readAll($pdo, 'produtos', 'status = 1 GROUP BY categoria ORDER BY categoria');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class="produtos-wrapper">
    <?php include 'partials/header.php'; ?>
    <main class="produtos-main">
        <h1 class="produtos-titulo">
            <?php 
                if ($categoriaSelecionada) {
                    echo ucwords($categoriaSelecionada);
                } else {
                    echo "Todos os Produtos";
                }
            ?>
        </h1>

        <div class="filtros">
            <a href="produtos.php" class="filtro-btn <?php echo !$categoriaSelecionada ? 'ativo' : ''; ?>">Todos</a>
            <?php foreach ($todasCategorias as $cat): ?>
                <?php $catNome = ucwords($cat['categoria']); ?>
                <a href="produtos.php?categoria=<?php echo urlencode($cat['categoria']); ?>" 
                   class="filtro-btn <?php echo ($categoriaSelecionada === $cat['categoria']) ? 'ativo' : ''; ?>">
                    <?php echo htmlspecialchars($catNome); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (count($produtos) > 0): ?>
            <div class="produtos-grid">
                <?php foreach ($produtos as $p): ?>
                    <div class="produto-card">
                        <img src="<?php echo htmlspecialchars($p['imagem'] ?: 'produto-padrao.jpg'); ?>" alt="<?php echo htmlspecialchars($p['item']); ?>">
                        <h3><?php echo htmlspecialchars($p['item']); ?></h3>
                        <p><?php echo htmlspecialchars($p['descricao']); ?></p>
                        <span class="preco">R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></span>
                        <a href="carrinho.php?add=<?php echo $p['id']; ?>" class="btn-comprar">Comprar</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="sem-resultado">
                <p>Nenhum produto encontrado nesta categoria.</p>
                <a href="produtos.php" class="btn">Ver todos os produtos</a>
            </div>
        <?php endif; ?>
    </main>
    <?php include 'partials/footer.php'; ?>
</div>
</body>
</html>