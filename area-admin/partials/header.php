<div class="admin-sidebar">
    <div class="logo">
        <a href="../logout-index.php"><img src="../imagens/logoEstrutec.png" alt="Estrutec"></a>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="estoque.php">Estoque</a></li>
            <li><a href="cadastro-produto.php">Cadastro de produto</a></li>
            <?php if ($_SESSION['papel'] === 'gerente'): ?>
                <li><a href="funcionarios.php">Funcionários</a></li>
                <li><a href="cadastro-funcionario.php">Novo Funcionário</a></li>
                <li><a href="clientes.php">Clientes</a></li>
            <?php endif; ?>
            <li><a href="pedidos.php">Pedidos</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</div>