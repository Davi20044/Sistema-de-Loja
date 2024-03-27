<?php
session_start();

// Verifica se as variáveis de sessão existem
if (!isset($_SESSION['vendas']) || empty($_SESSION['vendas']) || !isset($_SESSION['valor_recebido'])) {
    // Se não houver vendas ou valor recebido, redireciona para a página anterior
    header("Location: index.php");
    exit;
}

// Inicializa as variáveis
$total_compra = 0;
$troco = 0;
$valor_recebido = $_SESSION['valor_recebido'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja_1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calcula o total da compra
foreach ($_SESSION['vendas'] as $venda) {
    $cod_prod = $venda['cod_prod'];
    $quantidade = $venda['quantidade'];

    // Busca os detalhes do produto no banco de dados
    $sql = "SELECT preco_v FROM produtos WHERE cod_prod = '$cod_prod'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $preco_unitario = $row['preco_v'];
        $total_compra += $preco_unitario * $quantidade;
    }
}

// Verifica se o valor recebido é maior ou igual ao total da compra
if ($valor_recebido >= $total_compra) {
    // Calcula o troco
    $troco = $valor_recebido - $total_compra;

    // Atualiza o estoque
    foreach ($_SESSION['vendas'] as $venda) {
        $cod_prod = $venda['cod_prod'];
        $quantidade = $venda['quantidade'];

        // Atualiza o estoque do produto
        $sql_update_estoque = "UPDATE estoque SET estoque = estoque - $quantidade WHERE cod_prod = '$cod_prod'";
        $conn->query($sql_update_estoque);
    }

    // Limpa as variáveis de sessão
    unset($_SESSION['vendas']);
    unset($_SESSION['valor_recebido']);
} else {
    // Se o valor recebido for menor que o total da compra, exibe uma mensagem
    echo "<script>alert('O valor recebido é menor que o total da compra.'); window.location.href = 'calcular_total.php';</script>";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processar Venda</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div>
        <?php if ($valor_recebido >= $total_compra): ?>
        <h1>Compra Finalizada</h1>
        <p>Total da Compra: R$ <?php echo number_format($total_compra, 2, ',', '.'); ?></p>
        <p>Troco: R$ <?php echo number_format($troco, 2, ',', '.'); ?></p>
        <p>Obrigado pela compra!</p>
        <?php endif; ?>

        <form action="limpar.php" method="get">
            <input type="submit" value="Retornar à Página Inicial">
        </form>

    </div>
</body>
</html>
