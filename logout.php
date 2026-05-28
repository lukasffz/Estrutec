<?php
session_start();

// Atualiza online=0 ANTES de qualquer outra operação
if (isset($_SESSION['id_login'])) {
    require_once 'config/crud.php';
    $id = (int) $_SESSION['id_login'];
    update($pdo, 'cadastrados', ['online' => 0], "id_login = $id");
}

session_unset();
session_destroy();

header('Location: login.php');
exit;
