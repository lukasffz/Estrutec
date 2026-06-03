<?php
require_once 'config/crud.php';

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
<html>
<head>
    <title>Contato - Estrutec</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<main>
    <div class="form-container">
        <h2>Fale Conosco</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" target="_blank">
            <div class="form-group"><label>Nome Completo*</label><input type="text" name="nome" required></div>
            <div class="form-group"><label>E-mail*</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Telefone*</label><input type="text" name="telefone" required></div>
            <div class="form-group"><label>Mensagem*</label><textarea name="mensagem" rows="4" required></textarea></div>
            <button type="submit">Enviar via WhatsApp</button>
        </form>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>