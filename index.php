<?php
session_start();

// Inclui o arquivo que contém a função para limpar a sessão
require_once 'limpar_sessao.php';

// Verifica se a variável de sessão para limpar a sessão está definida e se é true
if (isset($_SESSION['limpar_sessao']) && $_SESSION['limpar_sessao'] === true) {
    // Chama a função para limpar a sessão
    limpar_sessao();

    // Remove a variável de sessão
    unset($_SESSION['limpar_sessao']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Loja</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="loja_1.ico">
    <script src="script.js"></script>
</head>
<body>

<div class="container">
    <h1>Sistema de Loja</h1>
    <button onclick="openPopup('compra')">Compra</button>
    <button onclick="openPopup('venda')">Venda</button>
    <button onclick="openPopup('estoque')">Estoque</button>
    <button onclick="openPopup('produtos')">Produtos</button>
    <button onclick="openPopup('fornecedores')">Fornecedores</button>
</div>

<!-- Popups -->
<div id="popup-compra" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('compra')">&times;</span>
        <!-- Conteúdo da página de compra -->
        <h2>Comprar Produtos</h2>
        <!-- Coloque aqui o conteúdo específico da página de compra -->
        <!-- Formulário para compra de produtos -->
        <h3>Digite as informaçõs do produto a ser comprado</h3>
        <form action="processar_compra.php" method="post">
            <label for="cod_produto">Código do Produto:</label>
            <input type="text" id="cod_produto" name="cod_produto" required><br><br>
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required><br><br>
            <input type="submit" value="Comprar">
        </form>
    </div>
</div>

<div id="popup-venda" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('venda')">&times;</span>
        <!-- Conteúdo da página de venda -->
        <?php
            // Verifica se uma sessão está ativa
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Configurações do banco de dados
            $servername = "localhost"; // Endereço do servidor MySQL
            $username = "root"; // Nome de usuário do MySQL
            $password = ""; // Senha do MySQL
            $dbname = "loja_1"; // Nome do banco de dados

            // Inicializa a variável de vendas na sessão, se não estiver definida
            if (!isset($_SESSION['vendas'])) {
                $_SESSION['vendas'] = [];
            }

            // Verifica se o formulário foi submetido
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Verifica se as variáveis do formulário existem
                if (isset($_POST['cod_prod']) && isset($_POST['quantidade'])) {
                    // Obtém os valores do formulário
                    $cod_prod = $_POST['cod_prod'];
                    $quantidade = $_POST['quantidade'];

                    // Conexão com o banco de dados
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Verifica a conexão
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Processa os dados do formulário e busca informações do produto no banco de dados
                    foreach ($cod_prod as $key => $value) {
                        $cod_prod_input = $conn->real_escape_string($cod_prod[$key]); // Prevenir SQL Injection
                        $sql = "SELECT cod_prod, nome_prod, preco_v FROM produtos WHERE cod_prod = '$cod_prod_input'";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $_SESSION['vendas'][] = [
                                    'cod_prod' => $row['cod_prod'],
                                    'nome_prod' => $row['nome_prod'],
                                    'quantidade' => $quantidade[$key],
                                    'preco_v' => $row['preco_v']
                                ];
                            }
                        }
                    }

                    // Fecha a conexão com o banco de dados
                    $conn->close();
                }
            }
        ?>
        <h2>Venda</h2>
        <!-- Coloque aqui o conteúdo específico da página de venda -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="cod_prod">Código do Produto:</label>
        <input type="text" id="cod_prod" name="cod_prod[]" required><br><br>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade[]" required><br><br>
        <button type="submit">Adicionar Produto</button>
    </form>
    <br>
    <?php if (!empty($_SESSION['vendas'])): ?>
        <form action="calcular_total.php" method="post">
            <button type="submit">Confirmar Venda</button>
        </form>
        <br>
        <form action="limpar_sessao.php" method="post">
            <button type="submit" name="limpar_sessao">Cancelar compra</button>
        </form>
    <?php endif; ?>
    </div>
</div>

<div id="popup-estoque" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('estoque')">&times;</span>
        <!-- Conteúdo da página de estoque -->
        <h2>Estoque</h2>
        <!-- Coloque aqui o conteúdo específico da página de estoque -->
        <button onclick="openSubPopup('exibirEstoque')">Exibir Estoque</button>
        <button onclick="openSubPopup('removerEstoque')">Remover Produtos</button>
    </div>
</div>

<!-- Sub Popups -->
<div id="popup-exibirEstoque" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('exibirEstoque')">&times;</span>
        <!-- Conteúdo da página de exibirEstoque -->
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

            // Preparando a consulta SQL para recuperar os dados do estoque
            $sql = "SELECT cod_prod, nome_prod, estoque FROM estoque";

            // Executando a consulta SQL
            $result = $conn->query($sql);

            // Verificando se há resultados e exibindo-os
            if ($result->num_rows > 0) {
                echo "<h2>Lista de Produtos em Estoque</h2>";
                echo "<ul>";
                while($row = $result->fetch_assoc()) {
                    echo "<li>Código: " . $row["cod_prod"]. "</li>";
                    echo "<ul>";
                    echo "<li>Nome: " . $row["nome_prod"]. "</li>";
                    echo "<li>Estoque: " . $row["estoque"]. "</li>";
                    // Adicione outras colunas conforme necessário
                    echo "</ul>";
                }
                echo "</ul>";
            } else {
                echo "Nenhum produto encontrado no estoque.";
            }

            // Fechando a conexão com o banco de dados
            $conn->close();
        ?>

    </div>
</div>

<div id="popup-removerEstoque" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('removerEstoque')">&times;</span>
        <!-- Conteúdo da página de removerEstoque -->
        <h2>Remover do estoque</h2>
        <!-- Formulário para compra de produtos -->
        <h3>Digite as informaçõs do produto a ser removido</h3>
        <form method="post" action="estoque_remover.php">
            Código do Produto: <input type="text" name="cod_prod"><br>
            Quantidade a ser Removida: <input type="number" name="quantidade"><br><br>
            <input type="submit" name="submit" value="Remover do Estoque">
        </form>
    </div>
</div>

<div id="popup-produtos" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('produtos')">&times;</span>
        <!-- Conteúdo da página de produtos -->
        <h2>Produtos</h2>
        <button onclick="openSubPopup('exibirProdutos')">Exibir Produtos</button>
        <button onclick="openSubPopup('registrarProduto')">Registrar Produto</button>
        <button onclick="openSubPopup('removerProduto')">Remover Produto</button>
    </div>
</div>

<div id="popup-fornecedores" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('fornecedores')">&times;</span>
        <!-- Conteúdo da página de fornecedores -->
        <h2>Fornecedores Registrados</h2>
        <button onclick="openSubPopup('exibirFornecedores')">Exibir Fornecedores</button>
        <button onclick="openSubPopup('registrarFornecedor')">Registrar Fornecedor</button>
        <button onclick="openSubPopup('removerFornecedor')">Remover Fornecedor</button>
    </div>
</div>

<!-- Sub Popups -->
<div id="popup-exibirProdutos" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('exibirProdutos')">&times;</span>
        <!-- Conteúdo da página de produtos -->
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
                    echo "<li>Valor de Venda: " . $row["preco_v"]. "</li>";
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
    </div>
</div>

<div id="popup-registrarProduto" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('registrarProduto')">&times;</span>
        <!-- Conteúdo da página de registrarProduto -->
        <h2>Registrar produto</h2>
        <!-- Formulário para inserir informações do produto -->
        <form action="inserir_produto.php" method="post">
            Código do Produto: <input type="text" name="cod_prod"><br>
            Nome do Produto: <input type="text" name="nome_prod"><br>
            Preço do Produto: <input type="text" name="preco_prod"><br>
            Código do Fornecedor: <input type="text" name="cod_forn"><br>
            Valor de Compra: <input type="text" name="preco_c"><br>
            Valor de Venda: <input type="text" name="preco_v"><br>
            <input type="submit" value="Inserir Produto">
        </form>
    </div>
</div>

<div id="popup-removerProduto" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('removerProduto')">&times;</span>
        <!-- Conteúdo da página de removerProduto -->
        <h2>Remover Produto</h2>
        <form action="remover_produto.php" method="post">
            <label for="cod_prod">ID do Produto:</label>
            <input type="text" id="cod_prod" name="cod_prod" required>
            <button type="submit">Remover</button>
        </form>
    </div>
</div>

<div id="popup-exibirFornecedores" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('exibirFornecedores')">&times;</span>
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
        
            // Preparando a consulta SQL para recuperar fornecedores
            $sql = "SELECT * FROM fornecedor";
        
            // Executando a consulta SQL
            $result = $conn->query($sql);
        
            // Verificando se há resultados e exibindo-os
            if ($result->num_rows > 0) {
                echo "<h2>Lista de Fornecedores</h2>";
                echo "<ul>";
                while($row = $result->fetch_assoc()) {
                echo "<li>Código: " . $row["cod_forn"]. "</li>";
                echo "<ul>";
                echo "<li>Nome do Fornecedor: " . $row["nome_forn"]. "</li>";
                echo "<li>Email do Fornecedor: " . $row["email_forn"]. "</li>";
                echo "<li>Telefone do Fornecedor: " . $row["tel_forn"]. "</li>";
                echo "</ul>";
                }
                echo "</ul>";
            } else {
                echo "Nenhum fornecedor encontrado.";
            }
        
            // Fechando a conexão com o banco de dados
            $conn->close();
        ?>
    </div>
</div>

<div id="popup-registrarFornecedor" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('registrarFornecedor')">&times;</span>
        <!-- Conteúdo da página de registrarFornecedor -->
        <h2>Registrar Fornecedor</h2>
        <!-- Formulário para inserir informações do fornecedor -->
        <form action="inserir_fornecedor.php" method="post">
            Código do Fornecedor: <input type="text" name="cod_forn"><br>
            Nome do Fornecedor: <input type="text" name="nome_forn"><br>
            Email do Fornecedor: <input type="text" name="email_forn"><br>
            Telefone do Fornecedor: <input type="text" name="tel_forn"><br>
            <input type="submit" value="Inserir Fornecedor">
        </form>
    </div>
</div>

<div id="popup-removerFornecedor" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeSubPopup('removerFornecedor')">&times;</span>
        <!-- Conteúdo da página de removerFornecedor -->
        <h2>Remover Fornecedor</h2>
        <form action="remover_fornecedor.php" method="post">
            <label for="cod_forn">ID do Fornecedor:</label>
            <input type="text

"id="cod_forn" name="cod_forn" required>
            <button type="submit">Remover</button>
        </form>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>