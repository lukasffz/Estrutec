<?php
require_once 'config/crud.php';
// Busca categorias únicas do banco
$categorias = readAll($pdo, 'produtos', '1 GROUP BY categoria ORDER BY categoria');

// LÓGICA DO WHATSAPP ADICIONADA AQUI
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // INSIRA SEU NÚMERO AQUI (Apenas números: Código do país + DDD + Telefone)
    $numeroWhats = "5511999999999"; 

    $nome = strip_tags(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telefone = strip_tags(trim($_POST['telefone']));
    $mensagem = strip_tags(trim($_POST['mensagem']));

    $texto = "Olá! Gostaria de tirar uma dúvida:\n\n" .
             "*Nome:* " . $nome . "\n" .
             "*E-mail:* " . $email . "\n" .
             "*Telefone:* " . $telefone . "\n" .
             "*Mensagem:* " . $mensagem;

    $textoCodificado = urlencode($texto);
    $urlWhatsapp = "https://wa.me/{$numeroWhats}?text={$textoCodificado}";

    header("Location: " . $urlWhatsapp);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrutec - Soluções em Fundações e Estruturas</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class="home-wrapper">
    <?php include 'partials/header.php'; ?>
    <main class="home-main">
        <section class="hero-estrutec">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-headline">A base sólida para o seu <br><span>Projeto</span></h1>
                <p class="hero-tagline">Encontre tudo que você precisa em ferragens, concretagem e estruturas em um só lugar.<br> Navegue por nossas categorias e agilize sua obra hoje mesmo.</p>
                <a href="produtos.php" class="hero-button">COMPRE JÁ</a>
            </div>
        </section>
        <div class="home-categorias">
            <h2 class="home-titulo">Categorias de Produtos</h2>
            <br>
            <p class="home-subtitulo">Escolha uma categoria e encontre os melhores materiais para sua obra</p>
            <br>
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
        <section class="TextoSobre" id="sobre">
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
        <section>
            <div class="form-container">
                <h2>Tire Dúvidas</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" target="_blank">
                    <div class="form-group"><label>Nome Completo*</label><input type="text" name="nome" required></div>
                    <div class="form-group"><label>E-mail*</label><input type="email" name="email" required></div>
                    <div class="form-group"><label>Telefone*</label><input type="text" name="telefone" required></div>
                    <div class="form-group"><label>Mensagem*</label><textarea name="mensagem" rows="4" required></textarea></div>
                    <button type="submit">Enviar via WhatsApp</button>
                </form>
            </div>
        </section>
    </main>
    <?php include 'partials/footer.php'; ?>
</div>
</body>
</html>