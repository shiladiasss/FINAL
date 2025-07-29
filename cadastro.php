<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "habib";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


$nome = $cpf = $endereco = $cep = $telefone = $email = $data_nascimento = $senha = "";
$mensagem = "";
$tipo_mensagem = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = limpar_dados($_POST["nome"]);
    $cpf = limpar_dados($_POST["cpf"]);
    $endereco = limpar_dados($_POST["endereco"]);
    $cep = limpar_dados($_POST["cep"]);
    $telefone = limpar_dados($_POST["telefone"]);
    $email = limpar_dados($_POST["email"]);
    $data_nascimento = limpar_dados($_POST["data_nascimento"]);
    $senha = limpar_dados($_POST["senha"]);
    
    
    if (empty($nome) || empty($cpf) || empty($endereco) || empty($cep) || empty($email) || empty($senha)) {
        $mensagem = "Todos os campos obrigatórios devem ser preenchidos!";
        $tipo_mensagem = "erro";
    } else {
        
        $sql = "INSERT INTO cadastro (nome, cpf, endereco, cep, telefone, email, data_nascimento, senha) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $nome, $cpf, $endereco, $cep, $telefone, $email, $data_nascimento, $senha);
        
        if ($stmt->execute()) {
            $mensagem = "Cadastro realizado com sucesso!";
            $tipo_mensagem = "sucesso";
            
            $nome = $cpf = $endereco = $cep = $telefone = $email = $data_nascimento = $senha = "";
        } else {
            $mensagem = "Erro ao cadastrar: " . $stmt->error;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
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
    </style>
</head>
<body>
    <h1>Cadastro de Cliente</h1>
    
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="nome">Nome Completo:*</label>
            <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>
        </div>
        
        <div>
            <label for="cpf">CPF:*</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo $cpf; ?>" required>
        </div>
        
        <div>
            <label for="endereco">Endereço:*</label>
            <input type="text" id="endereco" name="endereco" value="<?php echo $endereco; ?>" required>
        </div>
        
        <div>
            <label for="cep">CEP:*</label>
            <input type="text" id="cep" name="cep" value="<?php echo $cep; ?>" required>
        </div>
        
        <div>
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" value="<?php echo $telefone; ?>">
        </div>
        
        <div>
            <label for="email">E-mail:*</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        
        <div>
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $data_nascimento; ?>">
        </div>
        
        <div>
            <label for="senha">Senha:*</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        
        <div>
            <button type="submit">Cadastrar</button>
        </div>
    </form>
</body>
</html>
