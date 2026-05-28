<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] === 'cliente') exit('Acesso negado');

$imagem = '';
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/png', 'image/jpeg'];
    if (!in_array($_FILES['imagem']['type'], $permitidos)) {
        exit('Apenas PNG e JPG são permitidos.');
    }
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $nomeImg = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens_projeto/' . $nomeImg);
    $imagem = 'imagens_projeto/' . $nomeImg;
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