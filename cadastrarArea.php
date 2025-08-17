<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conexao.php';
require 'tabaco.php';
session_start();

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodo = $_POST['periodoEscondido'] ?? $_GET['periodoSafra'] ?? null;

// Busca o tabaco pelo período
$stmt = $conecta->prepare("SELECT idtabaco FROM tabaco WHERE periodoSafra = ? AND produtor_idprodutor = ?");
$stmt->bind_param("si", $periodo, $produtor_id);
$stmt->execute();
$tabaco = $stmt->get_result()->fetch_assoc();
$idtabaco = $tabaco['idtabaco'] ?? null;

if (!$idtabaco) {
    header("Location: tabaco.php?mensagem=Safra não encontrada.");
    exit;
}

if (isset($_POST['salvar'])) {
    $idarea = !empty($_POST['idarea']) ? $_POST['idarea'] : null;

    if ($idarea) {
        // Atualizar área
        $sql = "UPDATE area 
                   SET nome=?, qtdPes=?, hectares=?, dataInicio=?, dataFim=?, variedades=?, produtos=?, pragasDoencas=?, agrotoxicos=?, mediaFolhas=?, colheitas=? 
                 WHERE idarea=?";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param(
    "sidsissssiii", // tipos corrigidos
    $_POST['nome'],              // s
    (int)$_POST['qtdPes'],       // i
    (float)$_POST['hectares'],   // d
    $_POST['dataInicio'],        // s
    $_POST['dataFim'],           // s
    $_POST['variedades'],        // s
    $_POST['produtos'],          // s
    $_POST['pragasDoencas'],     // s
    $_POST['agrotoxicos'],       // s
    (int)$_POST['mediaFolhas'],  // i
    (int)$_POST['colheitas'],    // i
    (int)$idarea                 // i
);
        $acaoMsg = "atualizada";
    } else {
        // Inserir área
        $sql = "INSERT INTO area 
                   (nome, qtdPes, hectares, dataInicio, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param(
    "sidsissssiii", // tipos corrigidos
    $_POST['nome'],              // s
    (int)$_POST['qtdPes'],       // i
    (float)$_POST['hectares'],   // d
    $_POST['dataInicio'],        // s
    $_POST['dataFim'],           // s
    $_POST['variedades'],        // s
    $_POST['produtos'],          // s
    $_POST['pragasDoencas'],     // s
    $_POST['agrotoxicos'],       // s
    (int)$_POST['mediaFolhas'],  // i
    (int)$_POST['colheitas'],    // i
    (int)$idarea                 // i
);
        $acaoMsg = "cadastrada";
    }

    if ($stmt->execute()) {
        $msg = "Área {$acaoMsg} com sucesso.";
    } else {
        $msg = "Erro ao salvar a área: " . $stmt->error;
    }

    header("Location: tabaco.php?periodoSafra=" . urlencode($periodo) . "&mensagem=" . urlencode($msg));
    exit;
}

if (isset($_POST['excluir']) && !empty($_POST['idarea'])) {
    $stmt = $conecta->prepare("DELETE FROM area WHERE idarea = ?");
    $stmt->bind_param("i", $_POST['idarea']);
    $stmt->execute();

    $msg = 'Área excluída com sucesso.';
    header("Location: tabaco.php?periodoSafra=" . urlencode($_POST['periodoEscondido']));
    exit;
}

