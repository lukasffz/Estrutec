<?php
require_once '../config/crud.php';
session_start();

if (!isset($_SESSION['id_login'])) {
    header('Location: ../login.php');
    exit;
}

$id_login = $_SESSION['id_login'];
$total = 0;
$itens = [];

foreach ($_SESSION['carrinho'] as $id_produto => $qtd) {
    $produto = read($pdo, 'produtos', "id = $id_produto");
    $subtotal = $produto['preco'] * $qtd;
    $total += $subtotal;
    $itens[] = [
        'id_produto' => $id_produto,
        'qtd' => $qtd,
        'preco' => $produto['preco']
    ];
}

$id_pedido = create($pdo, 'pedidos', [
    'id_login' => $id_login,
    'status' => 'Pendente',
    'total' => $total
]);

foreach ($itens as $item) {
    create($pdo, 'itens_pedido', [
        'id_pedido' => $id_pedido,
        'id_produto' => $item['id_produto'],
        'qtd_comprada' => $item['qtd'],
        'preco_unitario' => $item['preco']
    ]);

    $produtoAtual = read($pdo, 'produtos', "id = {$item['id_produto']}");
    $novaQtd = $produtoAtual['quantidade'] - $item['qtd'];
    update($pdo, 'produtos', ['quantidade' => $novaQtd], "id = {$item['id_produto']}");
}
unset($_SESSION['carrinho']);
header('Location: ../meus-pedidos.php?msg=sucesso');
exit;