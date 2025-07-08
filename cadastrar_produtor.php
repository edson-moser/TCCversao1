<?php 
    require 'conexao.php';

$nome = $_POST["nome"];
$email = $_POST["email"];
$cidade = $_POST["cidade"];
$telefone = $_POST["telefone"];
$login = $_POST["login"];
$senha = $_POST["senha"];
mysqli_query($conecta, "INSERT INTO produtor (nome, email, cidade, telefone, login, senha) VALUES ('$nome','$email', '$cidade', '$telefone', '$login', '$senha')");

?>

<?php
require 'conexao.php';


$nome = $_POST["nome"];
$cidade = $_POST["cidade"];
$estado = $_POST["estado"];
$telefone = $_POST["telefone"];
$data_nascimento = $_POST["data_nascimento"];
$email = $_POST["email"]; // é a chave de identificação


$sql = "UPDATE produtor SET 
    nome = '$nome', 
    cidade = '$cidade', 
    estado = '$estado', 
    telefone = '$telefone', 
    data_nascimento = '$data_nascimento'
    WHERE email = '$email'";

if (mysqli_query($conecta, $sql)) {
    header('Location: produtor.php?status=sucesso');
} else {
    echo "Erro ao atualizar dados: " . mysqli_error($conecta);
}
?>