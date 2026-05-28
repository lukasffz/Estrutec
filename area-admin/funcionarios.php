<?php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';
$funcionarios = readAll($pdo, 'cadastrados', "papel IN ('funcionario','gerente')");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/admin-style.css">
    <style>
        .badge-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-online {
            background-color: #14532d;
            color: #4ade80;
        }
        .badge-offline {
            background-color: #1e293b;
            color: #64748b;
        }
    </style>
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="admin-main">
    <h2>Funcionários</h2>

    <?php if (isset($_GET['sucesso'])): ?>
        <p style="color:#4ade80; margin-bottom:1rem;">✔ Funcionário atualizado com sucesso.</p>
    <?php endif; ?>

    <?php if (isset($_GET['deletado'])): ?>
        <p style="color:#4ade80; margin-bottom:1rem;">✔ Funcionário excluído com sucesso.</p>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Papel</th>
                    <th>Online</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionarios as $f): ?>
                <tr>
                    <td><?= $f['id_login'] ?></td>
                    <td><?= htmlspecialchars($f['nome']) ?></td>
                    <td><?= htmlspecialchars($f['email']) ?></td>
                    <td><?= $f['telefone'] ?></td>
                    <td><?= $f['papel'] ?></td>
                    <td>
                        <?php if (!empty($f['online'])): ?>
                            <span class="badge-status badge-online">● Online</span>
                        <?php else: ?>
                            <span class="badge-status badge-offline">● Offline</span>
                        <?php endif; ?>
                    </td>
                    <td class="acoes">
                        <!-- Botão Editar -->
                        <a href="editar-funcionario.php?id=<?= $f['id_login'] ?>"
                           class="btn-acao btn-editar"
                           title="Editar">
                            <img src="../imagens/edit.png" width="16" height="16" alt="Editar">
                        </a>

                        <!-- Botão Excluir -->
                        <?php if ($f['id_login'] !== $_SESSION['id_login']): ?>
                            <?php $nomeSeguro = htmlspecialchars($f['nome'], ENT_QUOTES); ?>
                            <form method="POST" action="deletar-funcionario.php"
                                  style="display:inline;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir <?= $nomeSeguro ?>? Esta ação não pode ser desfeita.')">
                                <input type="hidden" name="id" value="<?= $f['id_login'] ?>">
                                <button type="submit" class="btn-acao btn-lixeira" title="Excluir">
                                    <img src="../imagens/delete.png" width="16" height="16" alt="Excluir">
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn-acao" disabled
                                    title="Você não pode excluir a si mesmo"
                                    style="background-color:#2e4266; color:#555; cursor:not-allowed; opacity:0.4;">
                                <img src="../imagens/delete.png" width="16" height="16" alt="Excluir">
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
