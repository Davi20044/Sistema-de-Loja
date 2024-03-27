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
$sql = "SELECT * FROM produtos";

// Executando a consulta SQL
$result = $conn->query($sql);

// Verificando se há resultados e exibindo-os
if ($result->num_rows > 0) {
    echo "<h2>Lista de Produtos</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>Código: " . $row["cod_prod"]. "</li>";
        echo "<ul>";
        echo "<li>Nome: " . $row["nome_prod"]. "</li>";
        echo "<li>Codigo do Fornecedor: " . $row["cod_forn"]. "</li>";
        echo "<li>Valor de compra: " . $row["preco_c"]. "</li>";
        echo "<li>Valor de compra: " . $row["preco_v"]. "</li>";
        // Adicione outras colunas conforme necessário
        echo "</ul>";
    }
    echo "</ul>";
} else {
    echo "Nenhum produto encontrado.";
}

// Fechando a conexão com o banco de dados
$conn->close();
?>