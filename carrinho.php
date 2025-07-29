<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "habib";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$cod_produto = $data_compra = $preco = $forma_pagamento = $nome_cliente = $quantidade = $total = "";
$success_message = $error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $cod_produto = test_input($_POST["cod_produto"]);
    $data_compra = test_input($_POST["data_compra"]);
    $preco = test_input($_POST["preco"]);
    $forma_pagamento = test_input($_POST["forma_pagamento"]);
    $nome_cliente = test_input($_POST["nome_cliente"]);
    $quantidade = test_input($_POST["quantidade"]);
    $total = test_input($_POST["total"]);
    
    
    $stmt = $conn->prepare("INSERT INTO carrinho (cod_produto, data_compra, preco, forma_pagamento, nome_cliente, quantidade, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddsid", $cod_produto, $data_compra, $preco, $forma_pagamento, $nome_cliente, $quantidade, $total);
    
    
    if ($stmt->execute()) {
        $success_message = "Compra registrada com sucesso!";
        
        $cod_produto = $data_compra = $preco = $forma_pagamento = $nome_cliente = $quantidade = $total = "";
    } else {
        $error_message = "Erro ao registrar compra: " . $stmt->error;
    }
    
    $stmt->close();
}


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$current_date = date("Y-m-d");


if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($quantidade) && !empty($preco)) {
    $total = $quantidade * $preco;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
</head>
<body>
    <h1>Carrinho de Compras</h1>
    
    <?php if (!empty($success_message)): ?>
        <div style="color: green;"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="cod_produto">Código do Produto:</label>
            <input type="text" id="cod_produto" name="cod_produto" value="<?php echo $cod_produto; ?>" required>
        </div>
        
        <div>
            <label for="data_compra">Data da Compra:</label>
            <input type="date" id="data_compra" name="data_compra" value="<?php echo $current_date; ?>" required>
        </div>
        
        <div>
            <label for="preco">Preço Unitário:</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo $preco; ?>" required>
        </div>
        
        <div>
            <label for="forma_pagamento">Forma de Pagamento:</label>
            <select id="forma_pagamento" name="forma_pagamento" required>
                <option value="">Selecione...</option>
                <option value="Cartão de Crédito" <?php echo ($forma_pagamento == 'Cartão de Crédito') ? 'selected' : ''; ?>>Cartão de Crédito</option>
                <option value="Cartão de Débito" <?php echo ($forma_pagamento == 'Cartão de Débito') ? 'selected' : ''; ?>>Cartão de Débito</option>
                <option value="Dinheiro" <?php echo ($forma_pagamento == 'Dinheiro') ? 'selected' : ''; ?>>Dinheiro</option>
                <option value="Pix" <?php echo ($forma_pagamento == 'Pix') ? 'selected' : ''; ?>>Pix</option>
                <option value="Transferência" <?php echo ($forma_pagamento == 'Transferência') ? 'selected' : ''; ?>>Transferência</option>
            </select>
        </div>
        
        <div>
            <label for="nome_cliente">Nome do Cliente:</label>
            <input type="text" id="nome_cliente" name="nome_cliente" value="<?php echo $nome_cliente; ?>" required>
        </div>
        
        <div>
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" value="<?php echo $quantidade; ?>" required>
        </div>
        
        <div>
            <label for="total">Total:</label>
            <input type="number" id="total" name="total" step="0.01" value="<?php echo $total; ?>" readonly>
        </div>
        
        <button type="submit">Registrar Compra</button>
    </form>
</body>
</html>
<?php
$conn->close();
?>
