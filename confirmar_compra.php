<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação da Compra</title>
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

            // Verificar se o produto já existe no estoque
            $sql_estoque = "SELECT * FROM estoque WHERE cod_prod = '$cod_produto'";
            $result_estoque = $conn->query($sql_estoque);

            if ($result_estoque->num_rows > 0) {
                // Produto já existe no estoque, atualizar a quantidade
                $row_estoque = $result_estoque->fetch_assoc();
                $quantidade_atual = $row_estoque['estoque'];
                $nova_quantidade = $quantidade_atual + $quantidade;
                $sql_update_estoque = "UPDATE estoque SET estoque = $nova_quantidade WHERE cod_prod = '$cod_produto'";
                if ($conn->query($sql_update_estoque) === TRUE) {
                    echo "<p>Quantidade atualizada no estoque: $nova_quantidade</p>";
                } else {
                    echo "Erro ao atualizar o estoque: " . $conn->error;
                }
            } else {
                // Produto não existe no estoque, inserir novo registro
                $sql_insert_estoque = "INSERT INTO estoque (cod_prod, nome_prod, estoque) VALUES ('$cod_produto', '$nome_produto', $quantidade)";
                if ($conn->query($sql_insert_estoque) === TRUE) {
                    echo "<p>Produto adicionado ao estoque</p>";
                } else {
                    echo "Erro ao adicionar o produto ao estoque: " . $conn->error;
                }
            }

        } else {
            echo "<p>Produto não encontrado.</p>";
        }

        echo '<form action="index.php" method="get">';
        echo '<input type="submit" value="Retornar à Página Inicial">';
        echo '</form>';

        $conn->close();
        ?>

    </div>
</body>
</html>