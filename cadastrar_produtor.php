
<?php
require 'conexao.php';
include('protect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    echo "<pre>";
print_r($_POST);
echo "</pre>";
    $produtor_id = $_SESSION['idprodutor'] ?? null;

    $nome = $_POST["nome"] ?? '';
    $cidade = $_POST["cidade"] ?? '';
    $estado = $_POST["estado"] ?? '';
    $telefone = $_POST["telefone"] ?? '';
    $data_nascimento = $_POST["dataNascimento"] ?? '';
    $email = $_POST["email"] ?? '';

    $sql = "UPDATE produtor SET nome=?, cidade=?, estado=?, telefone=?, dataNascimento=?, email=? WHERE idprodutor=?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("ssssssi", $nome, $cidade, $estado, $telefone, $data_nascimento, $email, $produtor_id);

    if ($stmt->execute()) {
        header('Location: produtor.php');
        exit;
    } else {
        echo "Erro ao atualizar dados: " . $stmt->error;
    }
}
?>
