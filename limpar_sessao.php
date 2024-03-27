<?php

// Verifica se uma sessão está ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Função para limpar as variáveis de sessão e destruir a sessão
function limpar_sessao() {
    // Limpa as variáveis de sessão das vendas se estiverem definidas
    if (isset($_SESSION['vendas'])) {
        unset($_SESSION['vendas']);
    }

    // Finalmente, destrói a sessão
    session_destroy();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limpar_sessao'])) {
    // Chama a função para limpar as variáveis de sessão e destruir a sessão
    limpar_sessao();

    // Redireciona para index.php
    header("Location: index.php");
    exit;
}
?>