<?php
require 'protect.php'; // garante sessão / $_SESSION['idprodutor']
require 'conexao.php';

header('Content-Type: application/json; charset=utf-8');

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

function resposta($arr, $code = 200){
    http_response_code($code);
    echo json_encode($arr);
    exit;
}

if ($acao === 'criar') {
    $produtor_id = intval($_POST['produtor_id'] ?? ($_SESSION['idprodutor'] ?? 0));
    $area = floatval(str_replace(',', '.', $_POST['area'] ?? 0));
    $qtd = intval($_POST['qtdEucalipto'] ?? 0);
    $dataPlantio = $_POST['dataPlantio'] ?? null;
    $dataCorte = $_POST['dataCorte'] ?? null;

    // validação básica
    if ($produtor_id <= 0 || $qtd <= 0 || empty($dataPlantio)) {
        resposta(['sucesso' => false, 'erro' => 'Parâmetros obrigatórios faltando'], 400);
    }

    $sql = "INSERT INTO eucalipto (area, qtdEucalipto, dataPlantio, dataCorte, produtor_idprodutor)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) resposta(['sucesso' => false, 'erro' => $conecta->error], 500);

    $bindOk = $stmt->bind_param("dissi", $area, $qtd, $dataPlantio, $dataCorte, $produtor_id);
    if (!$bindOk) resposta(['sucesso' => false, 'erro' => $stmt->error], 500);

    $ok = $stmt->execute();
    if ($ok) resposta(['sucesso' => true, 'id' => $conecta->insert_id]);
    else resposta(['sucesso' => false, 'erro' => $stmt->error], 500);
}

elseif ($acao === 'listar') {
    $produtor_id = intval($_GET['produtor_id'] ?? ($_SESSION['idprodutor'] ?? 0));
    if ($produtor_id <= 0) resposta(['sucesso' => false, 'erro' => 'produtor_id inválido'], 400);

    $sql = "SELECT ideucalipto, area, qtdEucalipto, dataPlantio, dataCorte, produtor_idprodutor FROM eucalipto WHERE produtor_idprodutor = ? ORDER BY dataPlantio DESC";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) resposta(['sucesso' => false, 'erro' => $conecta->error], 500);

    $stmt->bind_param("i", $produtor_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $dados = $res->fetch_all(MYSQLI_ASSOC);
    resposta($dados);
}

elseif ($acao === 'editar') {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) resposta(['sucesso' => false, 'erro' => 'ID inválido'], 400);

    $area = floatval(str_replace(',', '.', $_POST['area'] ?? 0));
    $qtd = intval($_POST['qtdEucalipto'] ?? 0);
    $dataPlantio = $_POST['dataPlantio'] ?? null;
    $dataCorte = $_POST['dataCorte'] ?? null;

    $sql = "UPDATE eucalipto SET area=?, qtdEucalipto=?, dataPlantio=?, dataCorte=? WHERE ideucalipto=?";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) resposta(['sucesso' => false, 'erro' => $conecta->error], 500);

    $bindOk = $stmt->bind_param("dissi", $area, $qtd, $dataPlantio, $dataCorte, $id);
    if (!$bindOk) resposta(['sucesso' => false, 'erro' => $stmt->error], 500);

    $ok = $stmt->execute();
    if ($ok) resposta(['sucesso' => true]);
    else resposta(['sucesso' => false, 'erro' => $stmt->error], 500);
}

elseif ($acao === 'deletar') {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) resposta(['sucesso' => false, 'erro' => 'ID inválido'], 400);

    $sql = "DELETE FROM eucalipto WHERE ideucalipto=?";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) resposta(['sucesso' => false, 'erro' => $conecta->error], 500);

    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    if ($ok) resposta(['sucesso' => true]);
    else resposta(['sucesso' => false, 'erro' => $stmt->error], 500);
}

else {
    resposta(['sucesso' => false, 'erro' => 'Ação inválida'], 400);
}
