<?php
session_start();

// Define uma variável de sessão para indicar que a página index.php deve limpar a sessão ao ser acessada
$_SESSION['limpar_sessao'] = true;

// Redireciona o usuário para a página index.php
header("Location: index.php");
exit;
?>