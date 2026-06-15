<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] === 'cliente') exit('Acesso negado');

$id_pedido = $_POST['id_pedido'];
$novoStatus = $_POST['status'];
update($pdo, 'pedidos', ['status' => $novoStatus], "id_pedido = $id_pedido");
header('Location: ../area-admin/pedidos.php');
exit;