<?php
// Configurações do banco de dados
$servername = "localhost"; // Endereço do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "loja_1"; // Nome do banco de dados

// Obtendo os dados do formulário
$cod_prod = $_POST['cod_prod'];
$nome_prod = $_POST['nome_prod'];
$estoque = $_POST['estoque'];

// Criando conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Preparando a consulta SQL para inserir os dados no estoque
$sql = "INSERT INTO estoque (cod_prod, nome_prod, estoque)
        VALUES ('$cod_prod', '$nome_prod', '$estoque')";

// Executando a consulta SQL
if ($conn->query($sql) === TRUE) {
    echo "Produto inserido com sucesso!";
} else {
    echo "Erro ao inserir produto: " . $conn->error;
}

// Fechando a conexão com o banco de dados
$conn->close();

// Redireciona para index.php
header("Location: index.php");
exit;
?>