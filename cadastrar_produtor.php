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