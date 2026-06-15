<?php
$requer_gerente = true;
include '../config/auth.php';
require_once '../config/crud.php';

/*Aceita apenas POST*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: funcionarios.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: funcionarios.php');
    exit;
}

/*Impede que o gerente logado exclua a si mesmo*/
if ($id === (int) $_SESSION['id_login']) {
    header('Location: funcionarios.php?erro_delete=1');
    exit;
}

/*Confirma que o alvo é um funcionário ou gerente (nunca um cliente)*/
$funcionario = read($pdo, 'cadastrados', "id_login = $id AND papel IN ('funcionario','gerente')");

if (!$funcionario) {
    header('Location: funcionarios.php');
    exit;
}

/*Exclui o funcionário, com o ON DELETE CASCADE do banco, remove endereços vinculados automaticamente*/
delete($pdo, 'cadastrados', "id_login = $id");

header('Location: funcionarios.php?deletado=1');
exit;
