<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="./cadastrop.css">
    <link rel="stylesheet" href="../_css/header.css">
    <link rel="stylesheet" href="../_css/main.css">
</head>

<body>

    <!--NAV BAR-->
    <div class="header-container">
        <ul>
            <li class="menu"><a href="http://localhost/construcao_oficial/menu/menu.html">Menu</a></li>
            <li class="login"><a href="http://localhost/construcao_oficial/login/login.php">Login</a></li>
            <li class="cadastroo"><a href="http://localhost/construcao_oficial/cadastroP/cadastroP.php"
                    class="texto-grande">Cadastro de
                    Prod.</a></li>
            <li class="cadastroo"><a href="http://localhost/construcao_oficial/cadastroM/cadastroM.php"
                    class="texto-grande">Cadastro
                    de Mov.</a></li>
            <li class="estoque"><a href="http://localhost/construcao_oficial/tabelas/tabelasProdutos.php">Estoque</a>
            </li>
            <li class="login">
                <a href="#" id="btn-logout">Sair (Logout)</a>
            </li>
            <li class="login"><img src="../imagens/people.png" id="icone-usuario"></li>

            <li><img src="../imagens/logo.png" alt="logo" class="logo"></li>
        </ul>
    </div>


    <!--Informaçoes do cadastro cliente-->
    <div id="info-usuario"
        style="display:none; position: absolute; right: 20px; top: 90px; background-color: #fcd378; border: 1px solid #ccc; padding: 15px; z-index: 100; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <h4 style="margin-top: 0;">Usuário Logado</h4>
        <p style="margin: 5px 0;">Nome: <strong id="display-nome"></strong></p>
        <p style="margin: 5px 0;">Email: <strong id="display-email"></strong></p>
    </div>

    <!--DIV DO QUADRADO GRANDE amarelo-->
    <div class="cards">
        <form method="POST">
            <div class="cadastro">
                <!--INPUTS PARA ADICIONAR-->
                Nome:<input type="text" name="nome" id="ids"><br>
                SKU:<input type="text" name="sku" id="ids"><br>
                Categoria:<input type="text" name="categoria" id="ids" required><br>
                Descrição:<input type="text" name="descricao" id="ids"><br>
                Cor:<input type="text" name="cor" id="ids"><br>
                Unidade de Medida:<input type="text" name="unidade_medida" id="ids"><br>
                Data de Criação:<input type="date" name="data_criacao" id="ids"><br>
                Textura:<input type="text" name="textura" id="ids"><br>
                Aplicação:<input type="text" name="aplicacao" id="ids"><br>
                Estoque Mínimo:<input type="number" step="0.01" name="estoque_minimo" id="ids"><br>
                <input type="submit" value="Enviar" id="env"><!--BOTAO ENVIAR-->
            </div>
        </form>
    </div>

    <script src="./cadastro.js"></script>
</body>

</html>

<?php


$servername = "localhost";
$database = "saep_db";
$username = "root";
$password = "";

$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $database
);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
echo "<p>Conectado com Sucesso</p>";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sku = $_POST['sku'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $cor = $_POST['cor'];
    $unidade_medida = $_POST['unidade_medida'];
    $data_criacao = $_POST['data_criacao'];
    $textura = $_POST['textura'];
    $aplicacao = $_POST['aplicacao'];
    $estoque_minimo = $_POST['estoque_minimo'];

    $stmt = $conn->prepare("INSERT INTO produto (nome, sku, categoria, descricao, cor, unidade_medida, data_criacao, textura, aplicacao, estoque_minimo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssd", $nome, $sku, $categoria, $descricao, $cor, $unidade_medida, $data_criacao, $textura, $aplicacao, $estoque_minimo);

    if ($stmt->execute()) {
        echo "<p class='text'>Comando executado com sucesso</p>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    mysqli_close($conn);
}

?>
</body>

</html>