<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "habib";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $email = $cpf = $password = "";
$success_message = $error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $cpf = test_input($_POST["cpf"]);
    $password = test_input($_POST["password"]);
    
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    

    $stmt = $conn->prepare("INSERT INTO adm (nome, email, cpf, senha) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $cpf, $hashed_password);
    
    
    if ($stmt->execute()) {
        $success_message = "Cadastro realizado com sucesso!";
        
        $name = $email = $cpf = $password = "";
    } else {
        $error_message = "Erro ao cadastrar: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
</head>
<body>
    <h1>Cadastro de Administrador</h1>
    
    <?php if (!empty($success_message)): ?>
        <div style="color: green;"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        
        <div>
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo $cpf; ?>" required>
        </div>
        
        <div>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
