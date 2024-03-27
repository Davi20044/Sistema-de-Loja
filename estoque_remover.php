<?php
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Dados do formulário
        $cod_prod = $_POST['cod_prod'];
        $quantidade = $_POST['quantidade'];

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

        // Consulta SQL para atualizar o estoque
        $sql = "UPDATE estoque SET estoque = estoque - $quantidade WHERE cod_prod = $cod_prod";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Estoque atualizado com sucesso!</p>";
        } else {
            echo "Erro ao atualizar o estoque: " . $conn->error;
        }

        // Fechando a conexão com o banco de dados
        $conn->close();
    }

// Redireciona para index.php
header("Location: index.php");
exit;
?>