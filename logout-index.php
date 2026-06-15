<?php
session_start();

if (isset($_SESSION['id_login'])) {
    require_once 'config/crud.php';
    $id = (int) $_SESSION['id_login'];
    update($pdo, 'cadastrados', ['online' => 0], "id_login = $id");
}

session_unset();
session_destroy();

header('Location: index.php');
exit;
