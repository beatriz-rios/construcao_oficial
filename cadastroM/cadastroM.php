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
        function verificarAlertas($conn)
        {
            $alertas = [];
            // Usa COALESCE(quantidade, 0) para garantir a verificação correta se o estoque for NULL
            $sql = "SELECT 
                        idproduto,
                        nome,
                        COALESCE(quantidade, 0) AS quantidade, 
                        estoque_minimo 
                    FROM produto 
                    WHERE COALESCE(quantidade, 0) < estoque_minimo";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $alertas[] = $row;
            }
            return $alertas;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_movimentacao'])) {
            $produto_id = mysqli_real_escape_string($conn, $_POST['produto_id']);
            $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
            $quantidade = (int)$_POST['quantidade']; // Quantidade deve ser um número
            $data_movimentacao = mysqli_real_escape_string($conn, str_replace('T', ' ', $_POST['data_movimentacao']) . ':00');
            $observacao = mysqli_real_escape_string($conn, $_POST['observacao']);

            if ($tipo == 'saida') {
                // CORREÇÃO: Usa COALESCE(quantidade, 0) para ler o estoque atual
                $sql_check = "SELECT COALESCE(quantidade, 0) as quantidade FROM produto WHERE idproduto = '$produto_id'";
                $result_check = mysqli_query($conn, $sql_check);
                $row = mysqli_fetch_assoc($result_check);
                
                $quantidade_atual = $row['quantidade'] ?? 0; // Se o produto não existir, será 0

                if ($quantidade_atual < $quantidade) {
                    echo "<div class='alert alert-danger'>Erro: Quantidade insuficiente em estoque. Disponível: " . $quantidade_atual . "</div>";
                } else {
                    // REGISTRO DE SAÍDA
                    $sql_mov = "INSERT INTO movimentacao (produto_idproduto, tipo_entrada_saida, quantidade, data_movimentacao, observacao) VALUES ('$produto_id', '$tipo', '$quantidade', '$data_movimentacao', '$observacao')";
                    if (mysqli_query($conn, $sql_mov)) {
                        // CORREÇÃO: Usa COALESCE(quantidade, 0) para subtrair, tratando NULL como 0
                        $sql_update = "UPDATE produto SET quantidade = COALESCE(quantidade, 0) - $quantidade WHERE idproduto = '$produto_id'";
                        if (mysqli_query($conn, $sql_update)) {
                            echo "<div class='alert alert-success'>Movimentação registrada e estoque atualizado com sucesso!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Movimentação registrada, mas erro ao atualizar estoque: " . mysqli_error($conn) . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao registrar movimentação: " . mysqli_error($conn) . "</div>";
                    }
                }
            } else {
                // REGISTRO DE ENTRADA
                $sql_mov = "INSERT INTO movimentacao
                          (produto_idproduto, tipo_entrada_saida, quantidade, data_movimentacao, observacao)
                          VALUES ('$produto_id', '$tipo', '$quantidade', '$data_movimentacao', '$observacao')";
                if (mysqli_query($conn, $sql_mov)) {
                    // CORREÇÃO: Usa COALESCE(quantidade, 0) para somar, tratando NULL como 0
                    $sql_update = "UPDATE produto SET quantidade = COALESCE(quantidade, 0) + $quantidade WHERE idproduto = '$produto_id'";
                    if (mysqli_query($conn, $sql_update)) {
                        echo "<div class='alert alert-success'>Movimentação registrada e estoque atualizado com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Movimentação registrada, mas erro ao atualizar estoque: " . mysqli_error($conn) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erro ao registrar movimentação: " . mysqli_error($conn) . "</div>";
                }
            }
        }

        $alertas = verificarAlertas($conn);
        if (!empty($alertas)) {
            echo "<div class='alert custom-alert-warning mt-4'><strong>ALERTA DE ESTOQUE BAIXO:</strong></div>";
            echo "<ul class='list-group mb-4'>";
            foreach ($alertas as $alerta) {
                echo "<li class='list-group-item custom-alert-warning'>Produto **" . htmlspecialchars($alerta['nome']) . "** está com estoque de **" . htmlspecialchars($alerta['quantidade']) . "**, abaixo do mínimo de **" . htmlspecialchars($alerta['estoque_minimo']) . "**</li>";
            }
            echo "</ul>";
        }
        ?>

        <h2 class="text-center custom-title mb-4">Registro de Movimentação</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 custom-card">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="produto_id" class="form-label">Produto:</label>
                            <select class="form-select" id="produto_id" name="produto_id" required>
                                <option value="">Selecione um Produto</option>
                                <?php
                                $sql_produtos = "SELECT idproduto, nome FROM produto ORDER BY nome";
                                $result_produtos = mysqli_query($conn, $sql_produtos);
                                while ($produto = mysqli_fetch_assoc($result_produtos)) {
                                    echo "<option value='" . htmlspecialchars($produto['idproduto']) . "'>" . htmlspecialchars($produto['nome']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Movimentação:</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecione o Tipo</option>
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade:</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_movimentacao" class="form-label">Data da Movimentação:</label>
                            <input type="datetime-local" class="form-control" id="data_movimentacao"
                                name="data_movimentacao" required>
                        </div>
                        <div class="mb-3">
                            <label for="observacao" class="form-label">Observação:</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                        <button type="submit" name="registrar_movimentacao" class="btn custom-btn w-100">Registrar
                            Movimentação</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center custom-title mb-4">Histórico de Movimentações</h2>
                <div class="custom-table-container table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Mov.</th>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Data/Hora</th>
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
                                echo "<td>" . htmlspecialchars($mov['nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($mov['tipo_entrada_saida']) . "</td>";
                                echo "<td>" . htmlspecialchars($mov['quantidade']) . "</td>";
                                echo "<td>" . htmlspecialchars($mov['data_movimentacao']) . "</td>";
                                echo "<td>" . htmlspecialchars($mov['observacao']) . "</td>";
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