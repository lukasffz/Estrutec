<?php
require_once '../config/crud.php';
include '../config/auth.php';

$categoriaFiltro = $_GET['categoria'] ?? '';
$where = $categoriaFiltro ? "categoria = '$categoriaFiltro'" : null;
$produtos = readAll($pdo, 'produtos', $where);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $acao = $_POST['acao'] ?? '';
    $produto = read($pdo, 'produtos', "id = $id");
    
    if ($produto) {
        if ($acao === 'add') {
            update($pdo, 'produtos', ['quantidade' => $produto['quantidade'] + 1], "id = $id");
        } elseif ($acao === 'remove' && $produto['quantidade'] > 0) {
            update($pdo, 'produtos', ['quantidade' => $produto['quantidade'] - 1], "id = $id");
        } 
        // Alterna o status de 1 para 0 ou de 0 para 1 (Liga/Desliga)
        elseif (isset($_POST['lixeira'])) {
            $novoStatus = $produto['status'] == 1 ? 0 : 1;
            update($pdo, 'produtos', ['status' => $novoStatus], "id = $id");
        } 
        elseif ($acao === 'edit') {
            header("Location: estoque.php?edit_id=$id" . ($categoriaFiltro ? "&categoria=$categoriaFiltro" : ""));
            exit;
        }
    }
    header("Location: estoque.php" . ($categoriaFiltro ? "?categoria=$categoriaFiltro" : ""));
    exit;
}

$editProduto = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $editProduto = read($pdo, 'produtos', "id = $editId");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estrutec - Estoque</title>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
<div class="admin-main">
    
    <div class="estoque-filtros">
        <img src="../icones/Filter.png" alt="Filtrar" class="icone-filtro">
        <?php
        $categoriasDB = $pdo->query("SELECT DISTINCT categoria FROM produtos")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categoriasDB as $cat): ?>
            <a href="estoque.php?categoria=<?= urlencode($cat['categoria']) ?>" class="tag-filtro <?= $categoriaFiltro === $cat['categoria'] ? 'tag-ativa' : '' ?>"><?= htmlspecialchars($cat['categoria']) ?></a>
        <?php endforeach; ?>
        <?php if ($categoriaFiltro): ?>
            <a href="estoque.php" class="tag-filtro">Limpar filtro</a>
        <?php endif; ?>
    </div>

    <?php if ($editProduto): ?>
    <div class="form-admin">
        <h3>Editar Produto: <?= htmlspecialchars($editProduto['item']) ?></h3>
        <form action="../crud/update-produto.php" method="POST">
            <input type="hidden" name="id" value="<?= $editProduto['id'] ?>">
            <input type="hidden" name="editar" value="1">

            <label>Nome do produto</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($editProduto['item']) ?>" required>

            <label>Categoria</label>
            <select name="categoria" required>
                <option value="Cimenticios" <?= $editProduto['categoria'] == 'Cimenticios' ? 'selected' : '' ?>>Cimentícios</option>
                <option value="Agregados" <?= $editProduto['categoria'] == 'Agregados' ? 'selected' : '' ?>>Agregados</option>
                <option value="Aço" <?= $editProduto['categoria'] == 'Aço' ? 'selected' : '' ?>>Aço</option>
                <option value="Pré-moldados" <?= $editProduto['categoria'] == 'Pré-moldados' ? 'selected' : '' ?>>Pré-moldados</option>
            </select>

            <label>Descrição</label>
            <textarea name="descricao" rows="3"><?= htmlspecialchars($editProduto['descricao']) ?></textarea>

            <label>Preço (ex.: 120.50)</label>
            <input type="text" name="preco" value="<?= number_format($editProduto['preco'], 2, ',', '') ?>" required>

            <label>Quantidade em estoque</label>
            <input type="number" name="quantidade" value="<?= $editProduto['quantidade'] ?>" min="0" required>

            <div class="campo-status">
                <input type="checkbox" name="status" id="status" value="1" <?= $editProduto['status'] == 1 ? 'checked' : '' ?>>
                <label for="status">Produto Ativo (Visível no site)</label>
            </div>

            <button type="submit">Salvar alterações</button>
            <a href="estoque.php<?= $categoriaFiltro ? "?categoria=$categoriaFiltro" : '' ?>" class="btn">Cancelar</a>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!$editProduto): ?>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Cód.</th>
                <th>Item</th>
                <th>Categoria</th>
                <th>Quantidade</th>
                <th>Preço Unit.</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?= $produto['id'] ?></td>
            <td><?= htmlspecialchars($produto['item']) ?></td>
            <td><?= htmlspecialchars($produto['categoria']) ?></td>

            <?php 
                $classeQtd = 'qtd-normal';
                if ($produto['quantidade'] <= 0) { $classeQtd = 'qtd-critica'; }
                elseif ($produto['quantidade'] <= 30) { $classeQtd = 'qtd-alerta'; }
            ?>
            <td class="<?= $classeQtd ?>"><?= $produto['quantidade'] ?></td>

            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>

            <td>R$ <?= number_format($produto['quantidade'] * $produto['preco'], 2, ',', '.') ?></td>

            <td>
                <span class="badge-status <?= $produto['status'] ? 'badge-ativo' : 'badge-inativo' ?>">
                    <?= $produto['status'] ? 'Ativo' : 'Inativo' ?>
                </span>
            </td>

            <td class="acoes">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $produto['id']; ?>">

                    <button type="submit" name="acao" value="add" class="btn-acao btn-add">+</button>
                    <button type="submit" name="acao" value="remove" class="btn-acao btn-remove">-</button>

                    <?php if ($produto['status'] == 1): ?>
                        <button type="submit" name="lixeira" class="btn-acao btn-lixeira">
                            <img src="../imagens/esconder.png" width="19px" alt="Ocultar">
                        </button>
                    <?php else: ?>
                        <button type="submit" name="lixeira" class="btn-acao btn-lixeira">
                            <img src="../imagens/visualizar.png" width="19px" alt="Exibir">
                        </button>
                    <?php endif; ?>

                    <button type="submit" name="acao" value="edit" class="btn-acao btn-editar">
                        <img src="../imagens/edit.png" width="19px" alt="Editar">
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

    <div class="estoque-rodape">
        <div class="rodape-item"><span class="rodape-label">Valor Total em Estoque:</span><span class="rodape-valor">R$ <?= number_format(array_sum(array_map(fn($p)=>$p['quantidade']*$p['preco'], $produtos)), 2, ',', '.') ?></span></div>
        <div class="rodape-item"><span class="rodape-label">Total de Itens:</span><span class="rodape-valor"><?= count($produtos) ?></span></div>
        <div class="rodape-item"><span class="rodape-label">Alertas de Reposição:</span><span class="rodape-valor rodape-alerta"><?= count(array_filter($produtos, fn($p)=>$p['quantidade'] <= 30)) ?></span></div>
    </div>
</div>
</body>
</html>