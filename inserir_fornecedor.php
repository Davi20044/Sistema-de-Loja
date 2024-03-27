<?php
// Configurações do banco de dados
$servername = "localhost"; // Endereço do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "loja_1"; // Nome do banco de dados

// Obtendo os dados do formulário
$cod_forn = $_POST['cod_forn'];
$nome_forn = $_POST['nome_forn'];
$email_forn = $_POST['email_forn'];
$tel_forn = $_POST['tel_forn'];

// Criando conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Preparando a consulta SQL para inserir os dados do fornecedor
$sql = "INSERT INTO fornecedor (cod_forn, nome_forn, email_forn, tel_forn)
        VALUES ('$cod_forn', '$nome_forn', '$email_forn', '$tel_forn')";

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