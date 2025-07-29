<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "habib";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$cod_carrinho = $total = $cartao_debito = $cartao_credito = $pix = $dinheiro = $transferencia = "";
$success_msg = $error_msg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $cod_carrinho = htmlspecialchars(strip_tags(trim($_POST["cod_carrinho"])));
    $total = htmlspecialchars(strip_tags(trim($_POST["total"])));
    $forma_pagamento = htmlspecialchars(strip_tags(trim($_POST["forma_pagamento"])));
    
    
    $cartao_debito = ($forma_pagamento == "cartao_debito") ? 1 : 0;
    $cartao_credito = ($forma_pagamento == "cartao_credito") ? 1 : 0;
    $pix = ($forma_pagamento == "pix") ? 1 : 0;
    $dinheiro = ($forma_pagamento == "dinheiro") ? 1 : 0;
    $transferencia = ($forma_pagamento == "transferencia") ? 1 : 0;
    
    
    $stmt = $conn->prepare("INSERT INTO forma_pagamento (cod_carrinho, total, cartao_debito, cartao_credito, pix, dinheiro, transferencia) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiii", $cod_carrinho, $total, $cartao_debito, $cartao_credito, $pix, $dinheiro, $transferencia);
    
    if ($stmt->execute()) {
        $success_msg = "Forma de pagamento registrada com sucesso!";
       
        $cod_carrinho = $total = "";
    } else {
        $error_msg = "Erro ao registrar: " . $stmt->error;
    }
    
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forma de Pagamento</title>
</head>
<body>
    <h1>Selecione a Forma de Pagamento</h1>
    
    <?php if (!empty($success_msg)): ?>
        <div style="color: green;"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_msg)): ?>
        <div style="color: red;"><?php echo $error_msg; ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="cod_carrinho">Código do Carrinho:</label>
            <input type="text" id="cod_carrinho" name="cod_carrinho" value="<?php echo $cod_carrinho; ?>" required>
        </div>
        
        <div>
            <label for="total">Valor Total (R$):</label>
            <input type="number" id="total" name="total" step="0.01" min="0" value="<?php echo $total; ?>" required>
        </div>
        
        <div>
            <label>Forma de Pagamento:</label><br>
            <input type="radio" id="cartao_debito" name="forma_pagamento" value="cartao_debito" required>
            <label for="cartao_debito">Cartão de Débito</label><br>
            
            <input type="radio" id="cartao_credito" name="forma_pagamento" value="cartao_credito">
            <label for="cartao_credito">Cartão de Crédito</label><br>
            
            <input type="radio" id="pix" name="forma_pagamento" value="pix">
            <label for="pix">PIX</label><br>
            
            <input type="radio" id="dinheiro" name="forma_pagamento" value="dinheiro">
            <label for="dinheiro">Dinheiro</label><br>
            
            <input type="radio" id="transferencia" name="forma_pagamento" value="transferencia">
            <label for="transferencia">Transferência Bancária</label><br>
        </div>
        
        <button type="submit">Confirmar Pagamento</button>
    </form>
</body>
</html>
