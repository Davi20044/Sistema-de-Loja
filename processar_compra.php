<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $servername = "localhost"; // Endereço do servidor MySQL
        $username = "root"; // Nome de usuário do MySQL
        $password = ""; // Senha do MySQL
        $dbname = "loja_1"; // Nome do banco de dados

        // Criar conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Obter dados do formulário
        $cod_produto = $_POST['cod_produto'];
        $quantidade = $_POST['quantidade'];

        // Consulta SQL para obter informações do produto
        $sql = "SELECT * FROM produtos WHERE cod_prod = '$cod_produto'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Exibir informações do produto e calcular o valor total da compra
            $row = $result->fetch_assoc();
            $nome_produto = $row['nome_prod'];
            $preco_unitario = $row['preco_c'];
            $valor_total = $preco_unitario * $quantidade;

            echo "<h2>Detalhes da Compra</h2>";
            echo "<p>Produto: $nome_produto</p>";
            echo "<p>Preço Unitário: R$ " . number_format($preco_unitario, 2, ',', '.') . "</p>";
            echo "<p>Quantidade: $quantidade</p>";
            echo "<p>Valor Total da Compra: R$ " . number_format($valor_total, 2, ',', '.') . "</p>";

            // Botão de confirmar
            echo '<form action="confirmar_compra.php" method="post">';
            echo '<input type="hidden" name="cod_produto" value="' . $cod_produto . '">';
            echo '<input type="hidden" name="quantidade" value="' . $quantidade . '">';
            echo '<input type="submit" value="Confirmar">';
            echo '</form>';

            // Botão de cancelar
            echo '<form action="index.php" method="get">';
            echo '<input type="submit" value="Cancelar">';
            echo '</form>';

        } else {
            echo "<p>Produto não encontrado.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>