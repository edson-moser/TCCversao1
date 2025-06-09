<?php 
$localhost = "localhost";
$user = "root";
$pass = "root";
$banco = "naturis-bd";

$conecta = mysqli_connect($localhost, $user, $pass, $banco);
//echo("uioh");

//var_dump($conecta);
$nome = "Teste de Nome";
mysqli_query($conecta, "INSERT INTO produtor (nome) VALUES ('$nome')");


$sql = mysqli_query($conecta, "SELECT * FROM produtor");
$sql = mysqli_query($conecta, "SELECT * FROM tabaco");
$sql = mysqli_query($conecta, "SELECT * FROM area");
$sql = mysqli_query($conecta, "SELECT * FROM eucalipto");
$sql = mysqli_query($conecta, "SELECT * FROM transacao");
$sql = mysqli_query($conecta, "SELECT * FROM listaTarefas");

echo(mysqli_num_rows($sql));

?>