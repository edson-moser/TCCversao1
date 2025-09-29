<?php 
require 'conexao.php';


$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$confirma_senha = $_POST["confirma_senha"];

if ($senha !== $confirma_senha) {
    // Senhas não conferem
    exit('Senhas não conferem!');
}

// Use password_hash para salvar a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Agora salve $senha_hash no banco, não $senha
$stmt = $conecta->prepare("INSERT INTO produtor (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha_hash);

if (!$stmt) {
    echo "Erro na preparação do cadastro: " . $conecta->error;
    exit;
}




if ($stmt->execute()) {
    // Busca o usuário recém cadastrado
    $stmt2 = $conecta->prepare("SELECT idprodutor, nome FROM produtor WHERE email = ?");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->bind_result($idprodutor, $nome);
    $stmt2->fetch();
    $stmt2->close();

    if(!isset($_SESSION)){
        session_start();
    }
    $_SESSION['idprodutor']= $idprodutor;
    $_SESSION['nome']= $nome;

    header("Location: LoginCadastro.php");
    exit;
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
