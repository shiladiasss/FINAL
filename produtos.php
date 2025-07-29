<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <h1>Cadastro de Produtos</h1>
    
    <form method="post" action="">
        <label for="codigo">Código do Produto:</label><br>
        <input type="text" id="codigo" name="codigo" required><br><br>
        
        <label for="nome">Nome do Produto:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="preco">Preço:</label><br>
        <input type="number" id="preco" name="preco" step="0.01" required><br><br>
        
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Cadastrar">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $host = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "habib";
        
        
        $conn = new mysqli($host, $usuario, $senha, $banco);
        
        
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        
        
        $codigo = $_POST['codigo'];
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $descricao = $_POST['descricao'];
        
        
        $sql = "INSERT INTO produto (codigo, nome, preco, descricao) 
                VALUES ('$codigo', '$nome', '$preco', '$descricao')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p>Produto cadastrado com sucesso!</p>";
        } else {
            echo "<p>Erro ao cadastrar: " . $conn->error . "</p>";
        }
        
        
        $conn->close();
    }
    ?>
</body>
</html>
