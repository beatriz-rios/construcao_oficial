<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $database = "saep_db";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user;
        $_SESSION['nome'] = $row['nome'];
        header("Location: ../menu/menu.php");
        exit;
    } else {
        $error = "Credenciais inválidas.";
    }
    $stmt->close();
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Funcionário</title>
    <link rel="stylesheet" href="login.css">

</head>

<body>
    <div id="mensagem-erro" style="
        display: none; 
        padding: 15px; 
        text-align: center; 
        font-weight: bold; 
        color: white; 
        margin-bottom: 20px;
        position: fixed; /* Fixa no topo */
        width: 100%;
        top: 0;
        left: 0;
        z-index: 9999;
    ">
    </div>

    <ul>
        <li class="menu"><a href="http://localhost/aulaPHP/construcao/menu/menu.php">Menu</a></li>
        <li class="login"><a href="http://localhost/aulaPHP/construcao/login/login.php">Login</a></li>
        <li class="cadastroo"><a
                href="http://localhost/aulaPHP/construcao/cadastroP/cadastroP.php">Cadastro de
                Produto</a></li>
        <li class="cadastroo"><a
                href="http://localhost/construcao_oficial/cadastroM/cadstroM.php">Cadastro
                de movimento</a></li>
        <li class="estoque"><a href="http://localhost/aulaPHP/construcao/cadastroP/tabelaProdutos.php">Estoque</a></li>
          <li class="login">
            <a href="#" id="btn-logout">Sair (Logout)</a>
        </li>
        <li class="login"><img src="../imagens/people.png" id="icone-usuario"></li>
      
    </ul>

    <div class="cards">
        <div class="form-container">
            <form id="formulario-login">
                <div class="loginn">
                    <label for="nome-input">Nome:</label>
                    <input type="text" name="nome" id="nome-input"> 

                    <label for="email-input">E-mail:</label>
                    <input type="email" name="e-mail" id="email-input"><br>

                    <label for="senha-input">Senha:</label>
                    <input type="password" name="senha" id="senha-input" required>

                    <button type="submit" id="btn-login">ENTRAR</button>
                </div>
            </form>
        </div>
    </div>

    <div id="info-usuario" class="info-painel">
        <p>Nome: <span id="display-nome"></span></p>
        <p>E-mail: <span id="display-email"></span></p>
    </div>


    <script src="./login.js">

    </script>
</body>

</html>