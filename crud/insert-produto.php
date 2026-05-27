<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] === 'cliente') exit('Acesso negado');

$imagem = '';
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $nomeImg = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens_projeto/' . $nomeImg);
    $imagem = $nomeImg;
}

create($pdo, 'produtos', [
    'item' => $_POST['nome'],
    'categoria' => $_POST['categoria'],
    'descricao' => $_POST['descricao'],
    'quantidade' => $_POST['quantidade'],
    'preco' => str_replace(',', '.', $_POST['preco']),
    'imagem' => $imagem
]);

header('Location: ../area-admin/estoque.php');
exit;