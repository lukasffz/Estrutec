<?php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: clientes.php');
    exit;
}

$cliente = read($pdo, 'cadastrados', "id_login = $id AND papel = 'cliente'");

if (!$cliente) {
    header('Location: clientes.php');
    exit;
}

$erros  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome     = trim($_POST['nome']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf      = trim($_POST['cpf']      ?? '');
    $ativo    = isset($_POST['ativo']) ? 1 : 0;

    if ($nome === '')                                  $erros[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $erros[] = 'E-mail inválido.';

    $emailExiste = read($pdo, 'cadastrados', "email = '$email' AND id_login != $id");
    if ($emailExiste) $erros[] = 'Este e-mail já está em uso por outro usuário.';

    if (empty($erros)) {
        $dados = [
            'nome'     => $nome,
            'email'    => $email,
            'telefone' => $telefone,
            'cpf'      => $cpf,
            'ativo'    => $ativo,
        ];

        $nova_senha = trim($_POST['nova_senha'] ?? '');
        if ($nova_senha !== '') {
            if (strlen($nova_senha) < 6) {
                $erros[] = 'A nova senha deve ter ao menos 6 caracteres.';
            } else {
                $dados['senha'] = password_hash($nova_senha, PASSWORD_DEFAULT);
            }
        }

        if (empty($erros)) {
            update($pdo, 'cadastrados', $dados, "id_login = $id");
            header('Location: clientes.php?sucesso=1');
            exit;
        }
    }

    $cliente = array_merge($cliente, $_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="./styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>

<div class="admin-main">
    <a href="clientes.php" class="link-voltar">← Voltar para Clientes</a>

    <h2>Editar Cliente <span class="badge-id">#<?= $id ?></span></h2>

    <?php if (!empty($erros)): ?>
        <div class="erro-lista">
            <ul>
                <?php foreach ($erros as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="form-admin" method="POST" action="editar-cliente.php?id=<?= $id ?>">

        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" required
               value="<?= htmlspecialchars($cliente['nome']) ?>">

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($cliente['email']) ?>">

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone"
               value="<?= htmlspecialchars($cliente['telefone']) ?>">

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf"
               value="<?= htmlspecialchars($cliente['cpf']) ?>">

        <label for="nova_senha">Nova senha <small style="color:#7A90B0">(deixe em branco para não alterar)</small></label>
        <input type="password" id="nova_senha" name="nova_senha"
               placeholder="Mínimo 6 caracteres" autocomplete="new-password">

        <div class="campo-status">
            <input type="checkbox" id="ativo" name="ativo" value="1"
                   <?= ($cliente['ativo'] ?? 1) ? 'checked' : '' ?>>
            <label for="ativo" style="margin:0">Cliente ativo</label>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>
</div>
</body>
</html>
