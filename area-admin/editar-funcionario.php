<?php
// area-admin/editar-funcionario.php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';

// --- Valida o ID recebido ---
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: funcionarios.php');
    exit;
}

$funcionario = read($pdo, 'cadastrados', "id_login = $id AND papel IN ('funcionario','gerente')");

if (!$funcionario) {
    header('Location: funcionarios.php');
    exit;
}

// --- Processa o formulário ---
$erros  = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome     = trim($_POST['nome']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf      = trim($_POST['cpf']      ?? '');
    $papel    = $_POST['papel']         ?? '';
    $ativo    = isset($_POST['ativo']) ? 1 : 0;

    // Validações básicas
    if ($nome === '')                         $erros[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';
    if (!in_array($papel, ['funcionario', 'gerente'])) $erros[] = 'Papel inválido.';

    // Garante e-mail único (exceto o próprio funcionário)
    $emailExiste = read($pdo, 'cadastrados', "email = '$email' AND id_login != $id");
    if ($emailExiste) $erros[] = 'Este e-mail já está em uso por outro usuário.';

    if (empty($erros)) {
        $dados = [
            'nome'     => $nome,
            'email'    => $email,
            'telefone' => $telefone,
            'cpf'      => $cpf,
            'papel'    => $papel,
            'ativo'    => $ativo,
        ];

        // Atualiza a senha apenas se preenchida
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
            header('Location: funcionarios.php?sucesso=1');
            exit;
        }
    }

    // Repopula com os dados enviados para mostrar os erros
    $funcionario = array_merge($funcionario, $_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>

<div class="admin-main">
    <a href="funcionarios.php" class="link-voltar">← Voltar para Funcionários</a>

    <h2>Editar Funcionário <span class="badge-id">#<?= $id ?></span></h2>

    <?php if (!empty($erros)): ?>
        <div class="erro-lista">
            <ul>
                <?php foreach ($erros as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="form-admin" method="POST"
          action="editar-funcionario.php?id=<?= $id ?>">

        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" required
               value="<?= htmlspecialchars($funcionario['nome']) ?>">

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required
               value="<?= htmlspecialchars($funcionario['email']) ?>">

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone"
               value="<?= htmlspecialchars($funcionario['telefone']) ?>">

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf"
               value="<?= htmlspecialchars($funcionario['cpf']) ?>">

        <label for="papel">Papel</label>
        <select id="papel" name="papel">
            <option value="funcionario"
                <?= $funcionario['papel'] === 'funcionario' ? 'selected' : '' ?>>
                Funcionário
            </option>
            <option value="gerente"
                <?= $funcionario['papel'] === 'gerente' ? 'selected' : '' ?>>
                Gerente
            </option>
        </select>

        <label for="nova_senha">Nova senha <small style="color:#7A90B0">(deixe em branco para não alterar)</small></label>
        <input type="password" id="nova_senha" name="nova_senha"
               placeholder="Mínimo 6 caracteres" autocomplete="new-password">

        <div class="campo-status">
            <input type="checkbox" id="ativo" name="ativo" value="1"
                   <?= ($funcionario['ativo'] ?? 1) ? 'checked' : '' ?>>
            <label for="ativo" style="margin:0">Funcionário ativo</label>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>
</div>
</body>
</html>
