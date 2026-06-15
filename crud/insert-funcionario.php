<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] !== 'gerente') exit('Acesso negado');

$senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
create($pdo, 'cadastrados', [
    'nome' => $_POST['nome'],
    'email' => $_POST['email'],
    'senha' => $senhaHash,
    'telefone' => $_POST['telefone'],
    'cpf' => $_POST['cpf'],
    'papel' => $_POST['papel']
]);
header('Location: ../area-admin/funcionarios.php');
exit;