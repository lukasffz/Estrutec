<?php
require_once '../config/crud.php';
session_start();
if ($_SESSION['papel'] !== 'gerente') exit('Acesso negado');
$id = $_POST['id'];
delete($pdo, 'produtos', "id = $id");
header('Location: ../area-admin/estoque.php');
exit;