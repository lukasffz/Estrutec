<?php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';
$clientes = readAll($pdo, 'cadastrados', "papel = 'cliente'");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/admin-style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">
    <h2>Clientes Cadastrados</h2>

    <?php if (isset($_GET['sucesso'])): ?>
        <p style="color:#4ade80; margin-bottom:1rem;">✔ Cliente atualizado com sucesso.</p>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= $c['id_login'] ?></td>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= $c['telefone'] ?></td>
                    <td><?= $c['cpf'] ?></td>
                    <td>
                        <?php if ($c['ativo']): ?>
                            <span class="badge-status badge-ativo">Ativo</span>
                        <?php else: ?>
                            <span class="badge-status badge-inativo">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="acoes">
                        <a href="./editar-cliente.php?id=<?= $c['id_login'] ?>"
                           class="btn-acao btn-editar"
                           title="Editar">
                            <img src="../imagens/edit.png" width="16" height="16" alt="Editar">
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
