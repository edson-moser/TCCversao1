<?php
$localhost = "localhost";
$user = "root";
$pass = "root";
$banco = "naturis";

$conecta = new mysqli($localhost, $user, $pass, $banco);

if ($conecta->connect_error) {
    die("Erro na conexão: " . $conecta->connect_error);
}
?>