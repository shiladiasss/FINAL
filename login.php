<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "habib";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$cpf = $senha = "";
$mensagem = "";
$tipo_mensagem = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = limpar_dados($_POST["cpf"]);
    $senha = limpar_dados($_POST["senha"]);

    if (empty($cpf) || empty($senha)) {
        $mensagem = "Todos os campos obrigatórios devem ser preenchidos!";
        $tipo_mensagem = "erro";
    } else {
        
        $sql = "SELECT * FROM cadastro WHERE cpf = ? AND senha = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cpf, $senha);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem = "Login realizado com sucesso!";
            $tipo_mensagem = "sucesso";
            
        } else {
            $mensagem = "CPF ou senha incorretos!";
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php if (!empty($mensagem)): ?>
        <div>
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="cpf">CPF:*</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo $cpf; ?>" required>
        </div>
        
        <div>
            <label for="senha">Senha:*</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        
        <div>
            <button type="submit">Entrar</button>
        </div>
    </form>
</body>
</html>
