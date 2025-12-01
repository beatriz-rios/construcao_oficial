<?php
$servername = "localhost";
$database = "saep_db";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./cadastrom.css">
    <link rel="stylesheet" href="../_css/header.css">
    <link rel="stylesheet" href="../_css/main.css">
</head>

<body>

    <div class="header-container">
        <ul>
            <li class="menu"><a href="http://localhost/construcao_oficial/menu/menu.html">Menu</a></li>
            <li class="login"><a href="http://localhost/construcao_oficial/login/login.php">Login</a></li>
            <li class="cadastroo"><a href="http://localhost/construcao_oficial/cadastroP/cadastroP.php">Cadastro de
                    Prod.</a></li>
            <li class="cadastroo"><a href="http://localhost/construcao_oficial/cadastroM/cadastroM.php">Cadastro
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

    <div id="info-usuario"
        style="display:none; position: absolute; right: 20px; top: 90px; background-color: #fcd378; border: 1px solid #ccc; padding: 15px; z-index: 100; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <h4 style="margin-top: 0;">Usuário Logado</h4>
        <p style="margin: 5px 0;">Nome: <strong id="display-nome"></strong></p>
        <p style="margin: 5px 0;">Email: <strong id="display-email"></strong></p>
    </div>

    <div class="container mt-4">

        <?php
        // Bloco de processamento PHP e alertas (Mantido, mas com classes Bootstrap para alertas)
        function verificarAlertas($conn)
        {
            // ... (função mantida) ...
            $alertas = [];
            // Nota: A coluna "quantidade" deve estar na tabela "produto" e ser o estoque atual
            $sql = "SELECT 
            idproduto,
            nome,
            quantidade,
            estoque_minimo FROM produto WHERE quantidade < estoque_minimo";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $alertas[] = $row;
            }
            return $alertas;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_movimentacao'])) {
            $produto_id = $_POST['produto_id'];
            $tipo = $_POST['tipo'];
            $quantidade = $_POST['quantidade'];
            $data_movimentacao = str_replace('T', ' ', $_POST['data_movimentacao']) . ':00';
            $observacao = $_POST['observacao'];

            // Campo $usuario removido pois não é usado no INSERT na tabela movimentacao
        
            if ($tipo == 'saida') {
                $sql_check = "SELECT quantidade FROM produto WHERE idproduto = $produto_id";
                $result_check = mysqli_query($conn, $sql_check);
                $row = mysqli_fetch_assoc($result_check);
                
                // Trata o caso de quantidade nula ou vazia
                $quantidade_atual = $row['quantidade'] ?? 0;

                if ($quantidade_atual < $quantidade) {
                    // CLASSE BOOTSTRAP: alert alert-danger (para erro)
                    echo "<div class='alert alert-danger'>Erro: Quantidade insuficiente em estoque. Disponível: " . $quantidade_atual . "</div>";
                } else {
                    // Registro de movimentação 
                    $sql_mov = "INSERT INTO movimentacao (produto_idproduto, tipo_entrada_saida, quantidade, data_movimentacao, observacao) VALUES ('$produto_id', '$tipo', '$quantidade', '$data_movimentacao', '$observacao')";
                    if (mysqli_query($conn, $sql_mov)) {
                        // Atualização de estoque (Saída: subtrai)
                        $sql_update = "UPDATE produto SET quantidade = quantidade - $quantidade WHERE idproduto = $produto_id";
                        if (mysqli_query($conn, $sql_update)) {
                            // CLASSE BOOTSTRAP: alert alert-success (para sucesso)
                            echo "<div class='alert alert-success'>Movimentação registrada e estoque atualizado com sucesso!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Movimentação registrada, mas erro ao atualizar estoque: " . mysqli_error($conn) . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao registrar movimentação: " . mysqli_error($conn) . "</div>";
                    }
                }
            } else {
                // Entrada
                $sql_mov = "INSERT INTO movimentacao
                          (produto_idproduto, tipo_entrada_saida, quantidade, data_movimentacao, observacao)
                          VALUES ('$produto_id', '$tipo', '$quantidade', '$data_movimentacao', '$observacao')";
                if (mysqli_query($conn, $sql_mov)) {
                    // Atualização de estoque (Entrada: soma)
                    $sql_update = "UPDATE produto SET quantidade = quantidade + $quantidade WHERE idproduto = $produto_id";
                    if (mysqli_query($conn, $sql_update)) {
                        // CLASSE BOOTSTRAP: alert alert-success (para sucesso)
                        echo "<div class='alert alert-success'>Movimentação registrada e estoque atualizado com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Movimentação registrada, mas erro ao atualizar estoque: " . mysqli_error($conn) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erro ao registrar movimentação: " . mysqli_error($conn) . "</div>";
                }
            }
        }

        // Exibir alertas de estoque
        $alertas = verificarAlertas($conn);
        if (!empty($alertas)) {
            echo "<h2 class='mt-4 text-center custom-title'>Alertas de Estoque Baixo</h2>";
            foreach ($alertas as $alerta) {
                // CLASSE BOOTSTRAP: alert alert-warning (customizada para a cor cinza-azulada)
                echo "<div class='alert alert-warning custom-alert-warning'>Produto: " . $alerta['nome'] . " - Quantidade atual: " . $alerta['quantidade'] . " - Mínimo: " . $alerta['estoque_minimo'] . "</div>";
            }
        }
        ?>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card custom-card">
                    <div class="card-body">
                        <h2 class="card-title text-center custom-title">Registrar Movimentação</h2>
                        <form method="post">
                            <div class="mb-3">
                                <label for="produto_id" class="form-label">Produto:</label>
                                <select class="form-select custom-input" name="produto_id" id="produto_id" required>
                                    <option value="">Selecione um produto</option>
                                    <?php
                                    $sql_produtos = "SELECT idproduto, nome FROM produto ORDER BY nome";
                                    $result_produtos = mysqli_query($conn, $sql_produtos);
                                    while ($produto = mysqli_fetch_assoc($result_produtos)) {
                                        echo "<option value='" . $produto['idproduto'] . "'>" . $produto['nome'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Movimentação:</label>
                                <select class="form-select custom-input" name="tipo" id="tipo" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade:</label>
                                <input type="number" step="0.01" class="form-control custom-input" name="quantidade"
                                    id="quantidade" required>
                            </div>

                            <div class="mb-3">
                                <label for="data_movimentacao" class="form-label">Data da Movimentação:</label>
                                <input type="datetime-local" class="form-control custom-input" name="data_movimentacao"
                                    id="data_movimentacao" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="observacao" class="form-label">Observação:</label>
                                <textarea class="form-control custom-input" name="observacao" id="observacao"
                                    rows="3"></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="registrar_movimentacao" class="btn custom-btn">Registrar
                                    Movimentação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mt-4 mb-3 text-center custom-title">Histórico de Movimentações</h2>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="table-responsive custom-table-container">
                    <table class="table table-striped table-bordered">
                        <thead style="background-color: #6C7A89; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Data</th>
                                <th>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_historico = "SELECT
                                                m.idmovimentacao,
                                                p.nome,
                                                m.tipo_entrada_saida,
                                                m.quantidade,
                                                m.data_movimentacao,
                                                m.observacao
                                              FROM movimentacao m
                                              JOIN produto p ON m.produto_idproduto = p.idproduto
                                              ORDER BY m.data_movimentacao DESC";

                            $result_historico = mysqli_query($conn, $sql_historico);
                            while ($mov = mysqli_fetch_assoc($result_historico)) {
                                echo "<tr>";
                                echo "<td>" . $mov['idmovimentacao'] . "</td>";
                                echo "<td>" . $mov['nome'] . "</td>";
                                echo "<td>" . $mov['tipo_entrada_saida'] . "</td>";
                                echo "<td>" . $mov['quantidade'] . "</td>";
                                echo "<td>" . $mov['data_movimentacao'] . "</td>";
                                echo "<td>" . $mov['observacao'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php mysqli_close($conn); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./cadastrom.js"></script>
</body>

</html>