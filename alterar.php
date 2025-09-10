<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "habib";

function exibirFormularioBusca($msg = '') {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Buscar Cadastro para Alterar</title>
    </head>
    <body>
        <h2>Buscar Cadastro para Alterar</h2>
        <?php if ($msg): ?>
            <p style="color:red;"><?php echo htmlspecialchars($msg); ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label for="buscar_cpf">Informe o CPF do cadastro:</label><br>
            <input type="text" id="buscar_cpf" name="buscar_cpf" required><br><br>
            <input type="submit" value="Buscar">
        </form>
    </body>
    </html>
    <?php
}


function exibirFormularioAlterar($dados, $msg = '') {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Alterar Cadastro</title>
    </head>
    <body>
        <h2>Alterar Dados do Cadastro</h2>
        <?php if ($msg): ?>
            <p style="color:green;"><?php echo htmlspecialchars($msg); ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <input type="hidden" name="cpf_original" value="<?php echo htmlspecialchars($dados['cpf']); ?>">

            <label for="cpf">CPF:</label><br>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($dados['cpf']); ?>" required><br><br>

            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required><br><br>

            <label for="endereco">Endereço:</label><br>
            <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dados['endereco']); ?>" required><br><br>

            <label for="cep">CEP:</label><br>
            <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($dados['cep']); ?>" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($dados['telefone']); ?>" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($dados['email']); ?>" required><br><br>

            <label for="data_nascimento">Data de Nascimento:</label><br>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($dados['data_nascimento']); ?>" required><br><br>

            <input type="submit" name="alterar" value="Alterar">
        </form>
        <br>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Buscar outro cadastro</a>
    </body>
    </html>
    <?php
}

$conexao = new mysqli($host, $user, $password, $dbname);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['alterar'])) {
        
        $cpf_original = $_POST['cpf_original'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $cep = $_POST['cep'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $email = $_POST['email'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';

        
        if (empty($cpf_original) || empty($cpf) || empty($nome) || empty($endereco) || empty($cep) || empty($telefone) || empty($email) || empty($data_nascimento)) {
            $dados = $_POST;
            $msg = "Por favor, preencha todos os campos.";
            exibirFormularioAlterar($dados, $msg);
            exit;
        }

        
        $sql = "UPDATE cadastro SET cpf = ?, nome = ?, endereco = ?, cep = ?, telefone = ?, email = ?, data_nascimento = ? WHERE cpf = ?";
        $stmt = $conexao->prepare($sql);
        if ($stmt === false) {
            die("Erro na preparação da query: " . $conexao->error);
        }
        $stmt->bind_param("ssssssss", $cpf, $nome, $endereco, $cep, $telefone, $email, $data_nascimento, $cpf_original);

        if ($stmt->execute()) {
            $stmt->close();
            $sql2 = "SELECT * FROM cadastro WHERE cpf = ?";
            $stmt2 = $conexao->prepare($sql2);
            $stmt2->bind_param("s", $cpf);
            $stmt2->execute();
            $result = $stmt2->get_result();
            if ($result->num_rows === 1) {
                $dados = $result->fetch_assoc();
                $msg = "Dados atualizados com sucesso!";
                exibirFormularioAlterar($dados, $msg);
            } else {
                echo "Erro: cadastro não encontrado após atualização.";
            }
            $stmt2->close();
        } else {
            $dados = $_POST;
            $msg = "Erro ao atualizar dados: " . $stmt->error;
            exibirFormularioAlterar($dados, $msg);
        }

    } elseif (isset($_POST['buscar_cpf'])) {
        
        $buscar_cpf = $_POST['buscar_cpf'];

        if (empty($buscar_cpf)) {
            exibirFormularioBusca("Por favor, informe um CPF válido.");
            exit;
        }

      
        $sql = "SELECT * FROM cadastro WHERE cpf = ?";
        $stmt = $conexao->prepare($sql);
        if ($stmt === false) {
            die("Erro na preparação da query: " . $conexao->error);
        }
        $stmt->bind_param("s", $buscar_cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $dados = $result->fetch_assoc();
            exibirFormularioAlterar($dados);
        } else {
            exibirFormularioBusca("Cadastro com CPF $buscar_cpf não encontrado.");
        }
        $stmt->close();

    } else {
        exibirFormularioBusca();
    }
} else {
    exibirFormularioBusca();
}

$conexao->close();
?>
