<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
</head>
<body>
    <h2>Lista de Usuários</h2>
    <a href="excluir.php">clientes</a>
    <?php
    require_once "conexao.php";

    // Excluir usuário se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cpf'])) {
        $cpf = $_POST['cpf']; // CPF geralmente é string, não usar intval
        $sql = "DELETE FROM cadastro WHERE cpf = ?";
        $stmt = $conexao->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $cpf); // "s" para string
            if ($stmt->execute()) {
                echo "<p>✅ Usuário com CPF $cpf foi excluído com sucesso.</p>";
            } else {
                echo "<p>❌ Erro ao excluir: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>❌ Erro na preparação da consulta: " . $conexao->error . "</p>";
        }
    }

    // Consulta todos os usuários
    $resultado = $conexao->query("SELECT cpf, nome FROM cadastro");

    if ($resultado->num_rows > 0) {
        echo "<form method='POST' action=''>";
        echo "<label for='cpf'>Selecione o usuário para excluir:</label><br>";
        echo "<select name='cpf' id='cpf' required>";
        while ($linha = $resultado->fetch_assoc()) {
            echo "<option value='{$linha['cpf']}'>CPF {$linha['cpf']} - {$linha['nome']}</option>";
        }
        echo "</select><br><br>";
        echo "<input type='submit' value='Excluir'>";
        echo "</form>";
    } else {
        echo "<p>⚠️ Nenhum usuário encontrado.</p>";
    }

    $conexao->close();
    ?>
</body>
</html>
