<?php 
require 'conexao.php';

// Capturar os dados do formulário
$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$confirma_senha = $_POST["confirma_senha"];
// Verificar se as senhas coincidem
if ($senha != $confirma_senha) {
    echo "Erro: As senhas não coincidem.";
    exit;
}

// Criptografar a senha
//$senha_criptografada = (string)password_hash($senha, PASSWORD_DEFAULT);


// Preparar o INSERT corretamente com placeholders
$stmt = $conecta->prepare("INSERT INTO produtor (nome, email, senha) VALUES ('$nome','$email', '$senha')");

if (!$stmt) {
    echo "Erro na preparação do cadastro: " . $conecta->error;
    exit;
}

// Bind dos parâmetros
//$stmt->bind_param("sss", $nome, $email, $senha_criptografada);




// Executar e verificar
if ($stmt->execute()) {
    if(!isset($_SESSION)){
        session_start();

    }
    $_SESSION['idprodutor']= $usuario['idprodutor'];
    $_SESSION['nome']= $usuario['nome'];

    header("Location: paginaInicial.php");
} else {
    if ($conecta->errno === 1062) { 
        echo "Erro: Este e-mail já está cadastrado.";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
}

$stmt->close();
$conecta->close();
?>
