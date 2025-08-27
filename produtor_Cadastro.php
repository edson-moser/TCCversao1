<?php 
require 'conexao.php';


$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$confirma_senha = $_POST["confirma_senha"];

if ($senha != $confirma_senha) {
    echo "Erro: As senhas não coincidem.";
    exit;
}

$criptografia= password_hash($senha, PASSWORD_DEFAULT);



$stmt = $conecta->prepare("INSERT INTO produtor (nome, email, senha) VALUES ('$nome','$email', '$criptografia')");

if (!$stmt) {
    echo "Erro na preparação do cadastro: " . $conecta->error;
    exit;
}




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
