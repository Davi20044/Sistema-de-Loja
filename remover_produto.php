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

// Verificar se o ID do item foi enviado através do formulário
if (isset($_POST['cod_prod'])) {
    // Limpar o ID do item para evitar SQL injection
    $cod_prod = mysqli_real_escape_string($conn, $_POST['cod_prod']);

    // Query para remover o item do banco de dados
    $sql = "DELETE FROM produtos WHERE cod_prod = '$cod_prod'";

    if ($conn->query($sql) === TRUE) {
        echo "Item removido com sucesso.";
    } else {
        echo "Erro ao remover o item: " . $conn->error;
    }
} else {
    echo "ID do item não foi recebido.";
}

// Fechar a conexão com o banco de dados
$conn->close();

// Redireciona para index.php
header("Location: index.php");
exit;
?>