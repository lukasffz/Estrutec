<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <div class="site-header__inner">
        <a href="index.php" class="logo">
            <img src="imagens/logoEstrutec.png" alt="Estrutec">
        </a>
        <nav class="nav-principal">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="index.php#sobre">Sobre</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php if (isset($_SESSION['id_login'])): ?>
                    <li><a href="carrinho.php">Carrinho</a></li>
                    <li><a href="meus-pedidos.php">Meus Pedidos</a></li>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>