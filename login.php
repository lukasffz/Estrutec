<?php
require_once 'config/crud.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario = read($pdo, 'cadastrados', "email = '$email'");
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['id_login'] = $usuario['id_login'];
        $_SESSION['nome']     = $usuario['nome'];
        $_SESSION['papel']    = $usuario['papel'];

        // Marca o usuário como online
        update($pdo, 'cadastrados', ['online' => 1], "id_login = {$usuario['id_login']}");

        if ($usuario['papel'] === 'cliente') {
            header('Location: index.php');
        } else {
            header('Location: area-admin/dashboard.php');
        }
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include './partials/header.php'; ?>
    
    <main class="pagina-login">
        <div class="form-container">
            <h2>Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required>
                </div>
                <button type="submit">Entrar</button>
                <?php if (isset($erro)): ?>
                    <p class="erro"><?= $erro ?></p>
                <?php endif; ?>
            </form>
            <p style="text-align:center; margin-top:1rem;">
                Não tem conta? <a href="cadastro.php" style="color:#16B2D4;">Criar Conta</a>
            </p>
        </div>
    </main>
    
    <?php include './partials/footer.php'; ?>
</body>
</html>