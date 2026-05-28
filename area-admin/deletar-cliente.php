<?php
// area-admin/deletar-cliente.php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clientes.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: clientes.php');
    exit;
}

// Confirma que o alvo é realmente um cliente
$cliente = read($pdo, 'cadastrados', "id_login = $id AND papel = 'cliente'");

if (!$cliente) {
    header('Location: clientes.php?erro_delete=1');
    exit;
}

// Exclui — ON DELETE CASCADE remove endereços vinculados automaticamente
delete($pdo, 'cadastrados', "id_login = $id");

header('Location: clientes.php?deletado=1');
exit;
