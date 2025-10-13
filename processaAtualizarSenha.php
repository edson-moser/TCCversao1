<?php
session_start();
include('conexao.php');

if(!isset($_SESSION['idprodutor'])){
    header("Location: login.php");
    exit;
}

$idprodutor = $_SESSION['idprodutor'];
$senhaAtual = $_POST['senhaAtual'];
$novaSenha = $_POST['novaSenha'];
$confirmaSenha = $_POST['confirmaSenha'];

// Busca o produtor
$sql = "SELECT senha FROM produtor WHERE idprodutor = ?";
$stmt = $conecta->prepare($sql);
$stmt->bind_param("i", $idprodutor);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $usuario = $result->fetch_assoc();

    // Verifica se a senha atual confere
    if(password_verify($senhaAtual, $usuario['senha'])){

        // Verifica se as novas senhas coincidem
        if($novaSenha === $confirmaSenha){

            // Criptografa e atualiza
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            $update = $conecta->prepare("UPDATE produtor SET senha = ? WHERE idprodutor = ?");
            $update->bind_param("si", $senhaHash, $idprodutor);
            $update->execute();

            echo "<script>alert('Senha atualizada com sucesso!'); window.location.href='produtor.php';</script>";

        } else {
            echo "<script>alert('As novas senhas não coincidem!'); history.back();</script>";
        }

    } else {
        echo "<script>alert('Senha atual incorreta!'); history.back();</script>";
    }

} else {
    echo "<script>alert('Usuário não encontrado!'); history.back();</script>";
}
?>
