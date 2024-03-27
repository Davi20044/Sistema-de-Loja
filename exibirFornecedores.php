<?php
// Configurações do banco de dados
$servername = "localhost"; // Endereço do servidor MySQL
$username = "root"; // Nome de usuário do MySQL
$password = ""; // Senha do MySQL
$dbname = "loja_1"; // Nome do banco de dados

// Criando conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Preparando a consulta SQL para recuperar todos os produtos
$sql = "SELECT * FROM fornecedor";

// Executando a consulta SQL
$result = $conn->query($sql);

// Verificando se há resultados e exibindo-os
if ($result->num_rows > 0) {
    echo "<h2>Lista de Fornecedores</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row["cod_forn"]. " - " . $row["nome_forn"]. "</li>";
    }
    echo "</ul>";
} else {
    echo "Nenhum produto encontrado.";
}

// Fechando a conexão com o banco de dados
$conn->close();
?>