<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] === 'cliente') exit('Acesso negado');

$id = $_POST['id'];

if (isset($_POST['editar'])) {
    $quantidade_segura = max(0, (int)$_POST['quantidade']);
    $status = isset($_POST['status']) ? 1 : 0;

    update($pdo, 'produtos', [
        'item' => $_POST['nome'],
        'categoria' => $_POST['categoria'],
        'descricao' => $_POST['descricao'],
        'preco' => str_replace(',', '.', $_POST['preco']),
        'quantidade' => $quantidade_segura,
        'status' => $status
    ], "id = $id");
} 
else {
    $acao = $_POST['acao'] ?? '';
    $produto = read($pdo, 'produtos', "id = $id");
    if ($produto) {
        if ($acao === 'add') {
            update($pdo, 'produtos', ['quantidade' => $produto['quantidade'] + 1], "id = $id");
        } elseif ($acao === 'remove' && $produto['quantidade'] > 0) {
            update($pdo, 'produtos', ['quantidade' => $produto['quantidade'] - 1], "id = $id");
        }
    }
}
header('Location: ../area-admin/estoque.php');
exit;
?>