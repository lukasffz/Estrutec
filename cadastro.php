<?php
require_once 'config/crud.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        $erro = 'As senhas não coincidem.';
    } else {
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        create($pdo, 'cadastrados', [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $senhaHash,
            'telefone' => $_POST['telefone'],
            'cpf' => $_POST['cpf'],
            'papel' => 'cliente'
        ]);
        header('Location: login.php?cadastro=ok');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include './partials/header.php'; ?>
    <div class="form-container">
        <h2>Cadastro de Usuário</h2>
        <form method="POST">
            <div class="form-group"><label>Nome Completo*</label><input type="text" name="nome" required></div>
            <div class="form-group"><label>E-mail*</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Senha*</label><input type="password" name="senha" required></div>
            <div class="form-group"><label>Confirmar Senha*</label><input type="password" name="confirmar_senha" required></div>
            <div class="form-group"><label>Telefone*</label><input type="text" name="telefone" required></div>
            <div class="form-group"><label>CPF*</label><input type="text" name="cpf" required></div>
            <button type="submit">Criar</button>
            <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
        </form>
        <p style="text-align:center; margin-top:1rem;">Já tem conta? <a href="login.php" style="color:#16B2D4;">Entrar</a></p>
    </div>
        <?php include './partials/footer.php'; ?>
</body>
</html>