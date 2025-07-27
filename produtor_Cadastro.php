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

$criptografia= password_hash($senha, PASSWORD_DEFAULT);


// Preparar o INSERT corretamente com placeholders
$stmt = $conecta->prepare("INSERT INTO produtor (nome, email, senha) VALUES ('$nome','$email', '$criptografia')");

if (!$stmt) {
    echo "Erro na preparação do cadastro: " . $conecta->error;
    exit;
}



// Executar e verificar
if ($stmt->execute()) {
    if(!isset($_SESSION)){
        session_start();

    }
    $_SESSION['idprodutor']= $usuario['idprodutor'];
    $_SESSION['nome']= $usuario['nome'];

    header("Location: LoginCadastro.php");
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
