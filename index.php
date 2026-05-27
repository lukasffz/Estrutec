<?php
require_once 'config/crud.php';
// Busca categorias únicas do banco
$categorias = readAll($pdo, 'produtos', '1 GROUP BY categoria ORDER BY categoria');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrutec - Soluções em Fundações e Estruturas</title>
    <link rel="stylesheet" href="styles/style.css">
    <!-- Estilos adicionais para home (já estão no style.css, mas garantimos) -->
</head>
<body>
<div class="home-wrapper">
    <?php include 'partials/header.php'; ?>
    <main class="home-main">
        <!-- Seção Hero -->
        <section class="hero-estrutec">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-headline">A base sólida para o seu <br><span>Projeto</span></h1>
                <p class="hero-tagline">Encontre tudo que você precisa em ferragens, concretagem e estruturas em um só lugar.<br> Navegue por nossas categorias e agilize sua obra hoje mesmo.</p>
                <a href="produtos.php" class="hero-button">COMPRE JÁ</a>
            </div>
        </section>
        <!-- Cards de categorias -->
        <div class="home-categorias">
            <h2 class="home-titulo">Categorias de Produtos</h2>
            <p class="home-subtitulo">Escolha uma categoria e encontre os melhores materiais para sua obra</p>
            <div class="home-grid">
                <?php foreach ($categorias as $cat): ?>
                    <?php 
                        // Capitaliza o nome da categoria (ex: "ferragens estruturais" => "Ferragens Estruturais")
                        $categoriaNome = ucwords($cat['categoria']);
                    ?>
                    <div class="home-card" onclick="window.location.href='produtos.php?categoria=<?php echo urlencode($cat['categoria']); ?>'">
                        <h2><?php echo htmlspecialchars($categoriaNome); ?></h2>
                        <p>Clique para ver os produtos</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <br>
        <section class="TextoSobre">
            <div class="imagem">
                <img src="imagens/empresaSobre.png" alt="Background sobre a estrutec">
            </div>
            <div class = "overlaySobre">
                <div class="conteudoSobre">
                    <h1>Sobre a Estrutec</h1>
                    <p>
                        A Estrutec é especializada no fornecimento de materiais para estrutura e fundação, atuando com foco exclusivo nas etapas mais críticas da construção civil. 
                        Seu trabalho é orientado por critérios de desempenho estrutural, conformidade técnica e confiabilidade operacional, garantindo segurança e durabilidade nas obras.
                    </p>
                    <br>
                    <p>
                        A empresa atende às fases de fundações, estruturas de concreto armado, 
                        sistemas pré-moldados e infraestrutura estrutural, mantendo seu posicionamento voltado exclusivamente à base das edificações, sem atuação em materiais de acabamento.
                    </p>
                </div>
            </div>
        </section>  
    </main>
    <?php include 'partials/footer.php'; ?>
</div>
</body>
</html>