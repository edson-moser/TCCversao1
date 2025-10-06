<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Recebe o e-mail
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail inválido.']);
    exit;
}

// Conexão com o banco (troque os dados abaixo)
$conn = new mysqli('localhost', 'root', 'root', 'naturis');
if ($conn->connect_error) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão.']);
    exit;
}

// Verifica se o e-mail existe
$stmt = $conn->prepare("SELECT idprodutor FROM produtor WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Gera um token seguro
    $token = bin2hex(random_bytes(16));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salva o token e a data de expiração no banco
    $stmt2 = $conn->prepare("UPDATE produtor SET token_recuperacao = ?, token_expira = ? WHERE email = ?");
    $stmt2->bind_param("sss", $token, $expira, $email);
    $stmt2->execute();
    $stmt2->close();

    // Monta o link de recuperação
    $link = "http://localhost/TCCversao1/ResetarSenha.php?token=$token";

    // Envia o e-mail
    $assunto = "Recuperação de senha";
    $mensagem = "Olá! Para redefinir sua senha, clique no link abaixo:\n$link\nEste link expira em 1 hora.";
    $headers = "From: no-reply@seusite.com\r\n";

    // mail($email, $assunto, $mensagem, $headers);
    echo json_encode(['sucesso' => true, 'link' => $link]);
    exit;
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não encontrado.']);
}

$stmt->close();
$conn->close();
?>
<script>
// RecuperarSenha.js
if (res.sucesso) {
    window.location.href = res.link;
}
</script>