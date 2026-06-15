<?php
// config/auth.php - Controle de acesso
session_start();

if (!isset($_SESSION['id_login'])) {
    header('Location: ../login.php');
    exit;
}
if (isset($requer_gerente) && $requer_gerente && $_SESSION['papel'] !== 'gerente') {
    header('Location: dashboard.php');
    exit;
}
?>