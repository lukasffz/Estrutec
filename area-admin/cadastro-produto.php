<?php include '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estrutec - Cadastrar Produto</title>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">
    <div class="form-admin">
        <h2>Cadastro de Produto</h2>
        <form action="../crud/insert-produto.php" method="POST" enctype="multipart/form-data">
            <label>Nome do produto</label>
            <input type="text" name="nome" required>

            <label>Preço (ex.: 120.50)</label>
            <input type="text" name="preco" required>

            <label>Quantidade</label>
            <input type="number" name="quantidade" required>

            <label>Categoria</label>
            <select name="categoria" required>
                <option value="">Selecione</option>
                <option value="Cimenticios">Cimentícios</option>
                <option value="Agregados">Agregados</option>
                <option value="Aço">Aço</option>
                <option value="Pré-moldados">Pré-moldados</option>
            </select>

            <label>Descrição</label>
            <textarea name="descricao" rows="3"></textarea>

            <label>Imagem do produto</label>
            <label for="imagem-input" class="file-upload-area">
                <span class="file-upload-icon">&#8679;</span>
                <span class="file-upload-text">Clique para selecionar uma imagem</span>
                <span class="file-upload-hint">PNG, JPG</span>
            </label>
            <input type="file" name="imagem" accept="image/png, image/jpg, image/jpeg" id="imagem-input" class="file-upload-input">

            <div class="campo-status">
                <input type="checkbox" name="status" id="status" value="1" checked>
                <label for="status">Produto Ativo (Visível no site)</label>
            </div>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
</div>
</body>
</html>