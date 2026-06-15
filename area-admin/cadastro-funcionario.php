<?php
$requer_gerente = true;
include '../config/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">
    <div class="form-admin">
        <h2>Cadastro de Funcionário</h2>
        <form action="../crud/insert-funcionario.php" method="POST">
            <label>Nome Completo*</label><input type="text" name="nome" required>
            <label>E-mail*</label><input type="email" name="email" required>
            <label>Senha*</label><input type="password" name="senha" required>
            <label>Telefone*</label><input type="text" name="telefone" required>
            <label>CPF*</label><input type="text" name="cpf" required>
            <label>Papel*</label>
            <select name="papel" required>
                <option value="funcionario">Funcionário</option>
                <option value="gerente">Gerente</option>
            </select>
            <button type="submit">Criar</button>
        </form>
    </div>
</div>
</body>
</html>