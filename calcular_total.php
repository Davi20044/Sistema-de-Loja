<?php
session_start();

// Verifica se existe a variável de vendas na sessão
if (!isset($_SESSION['vendas']) || empty($_SESSION['vendas'])) {
    // Se não houver vendas, redireciona de volta para a página anterior
    header("Location: index.php");
    exit;
}

// Inicializa o total da compra
$total_compra = 0;

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja_1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Processa os dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o campo de valor recebido foi enviado
    if (isset($_POST['valor_recebido'])) {
        $valor_recebido = $_POST['valor_recebido'];

        // Salva o valor recebido em uma variável de sessão
        $_SESSION['valor_recebido'] = $valor_recebido;

            // Verifica se há estoque suficiente para os itens na compra
            $estoque_suficiente = true;
            foreach ($_SESSION['vendas'] as $venda) {
                $cod_prod = $venda['cod_prod'];
                $quantidade = $venda['quantidade'];

                // Consulta o estoque do produto
                $sql_estoque = "SELECT estoque FROM estoque WHERE cod_prod = '$cod_prod'";
                $result_estoque = $conn->query($sql_estoque);
                if ($result_estoque->num_rows > 0) {
                    $row_estoque = $result_estoque->fetch_assoc();
                    $estoque_disponivel = $row_estoque['estoque'];
                    if ($quantidade > $estoque_disponivel) {
                        $estoque_suficiente = false;
                        break;
                    }
                }
            }

            if ($estoque_suficiente) {
                // Redireciona para a página de processamento da venda
                header("Location: processar_venda.php");
                exit;
            } else {
                // Exibe uma mensagem de erro se o estoque não for suficiente ou o valor recebido for menor que o total da compra
                echo "<script>alert('Quantidade insuficiente em estoque para completar a compra.');</script>";
            }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular Total da Compra</title>
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

        table {
            border-collapse: collapse;
            margin-bottom: 20px;
            align-items: center; 
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div>
    <h1>Detalhes da Compra</h1>
    <table>
        <thead>
            <tr>
                <th>Código do Produto</th>
                <th>Nome do Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['vendas'] as $venda): ?>
                <?php
                $cod_prod = $venda['cod_prod'];
                $quantidade = $venda['quantidade'];

                // Busca os detalhes do produto no banco de dados
                $sql = "SELECT nome_prod, preco_v FROM produtos WHERE cod_prod = '$cod_prod'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nome_prod = $row['nome_prod'];
                    $preco_unitario = $row['preco_v'];
                    $total_item = $preco_unitario * $quantidade;
                    $total_compra += $total_item;
                }
                ?>
                <tr>
                    <td><?php echo $cod_prod; ?></td>
                    <td><?php echo $nome_prod; ?></td>
                    <td><?php echo $quantidade; ?></td>
                    <td>R$ <?php echo number_format($preco_unitario, 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($total_item, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Total da Compra: R$ <?php echo number_format($total_compra, 2, ',', '.'); ?></h1>

    <!-- Formulário para inserir o valor recebido -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="valor_recebido">Valor Recebido:</label>
        <input type="text" id="valor_recebido" name="valor_recebido" required><br><br>
        <button type="submit">Finalizar Compra</button>
    </form>

    <!-- Botão para finalizar compra -->
    <form action="limpar.php" method="post">
        <button type="submit">Cancelar Compra</button>
    </form>
</div>
</body>
</html>
