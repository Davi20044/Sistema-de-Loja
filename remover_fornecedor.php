<?php
// Conexão com o banco de dados (substitua os valores conforme sua configuração)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja_1";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se o ID do fornecedor foi enviado através do formulário
if (isset($_POST['cod_forn'])) {
    // Limpar o ID do fornecedor para evitar SQL injection
    $cod_forn = mysqli_real_escape_string($conn, $_POST['cod_forn']);

    // Query para remover o fornecedor do banco de dados
    $sql = "DELETE FROM fornecedor WHERE cod_forn = '$cod_forn'";

    if ($conn->query($sql) === TRUE) {
        echo "Fornecedor removido com sucesso.";
    } else {
        echo "Erro ao remover o fornecedor: " . $conn->error;
    }
} else {
    echo "ID do fornecedor não foi recebido.";
}

// Fechar a conexão com o banco de dados
$conn->close();

// Redireciona para index.php
header("Location: index.php");
exit;
?>