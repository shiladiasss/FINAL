<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "habib";

$conexao = new mysqli($servername, $username, $password, $dbname);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$email = $senha = "";
$mensagem = "";
$tipo_mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = limpar_dados($_POST["email"]);
    $senha = limpar_dados($_POST["senha"]);

    if (empty($email) || empty($senha)) {
        $mensagem = "Por favor, preencha o e-mail e a senha.";
        $tipo_mensagem = "erro";
    } else {
        $sql = "SELECT * FROM cadastro WHERE email = ? LIMIT 1";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();

            if ($senha === $usuario['senha']) {
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $mensagem = "Login realizado com sucesso! Bem-vindo(a), " . htmlspecialchars($usuario['nome']) . ".";
                $tipo_mensagem = "sucesso";

            } else {
                $mensagem = "Senha incorreta. Tente novamente.";
                $tipo_mensagem = "erro";
            }
        } else {
            $mensagem = "Usuário não encontrado. <a href='cadastro.php'>Cadastre-se aqui</a>.";
            $tipo_mensagem = "erro";
        }

        $stmt->close();
    }
}

function limpar_dados($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        .mensagem {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        form {
            max-width: 300px;
            margin: auto;
        }
        div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            width: 100%;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .link-cadastro {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Login</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required />
        </div>
        <button type="submit">Entrar</button>
    </form>

    <div class="link-cadastro">
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    </div>
</body>
</html>
