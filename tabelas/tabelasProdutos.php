<?php
$servername = "localhost";
$database = "saep_db";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$sql = "
    SELECT
        p.idproduto,
        p.nome,
        p.sku,
        p.categoria,
        p.descricao,
        p.cor,
        p.unidade_medida,
        p.data_criacao,
        p.textura,
        p.aplicacao,
        p.estoque_minimo,
        p.quantidade AS quantidade_atual
    FROM produto p
    ORDER BY p.nome
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Produtos</title>
    <link rel="stylesheet" href="./tabelaProdutos.css">
    <link rel="stylesheet" href="../_css/header.css">

</head>

<body>

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

            <div id="info-usuario"
                style="display:none; position: absolute; right: 20px; top: 90px; background-color: #fcd378; border: 1px solid #ccc; padding: 15px; z-index: 100; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <h4 style="margin-top: 0;">Usuário Logado</h4>
                <p style="margin: 5px 0;">Nome: <strong id="display-nome"></strong></p>
                <p style="margin: 5px 0;">Email: <strong id="display-email"></strong></p>
            </div>
        </ul>
    </div>
    <h1>Tabela de Produtos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>SKU</th>
                <th>Categoria</th>
                <th>Descrição</th>
                <th>Cor</th>
                <th>Unidade de Medida</th>
                <th>Data de Criação</th>
                <th>Textura</th>
                <th>Estoque Mínimo</th>
                <th>Quantidade Atual</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["idproduto"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nome"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["sku"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["categoria"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["descricao"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["cor"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["unidade_medida"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["data_criacao"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["textura"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["estoque_minimo"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["quantidade_atual"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>Nenhum produto encontrado</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="tabelaProduto.js" defer></script>

    <?php
    mysqli_close($conn);
    ?>
</body>

</html>