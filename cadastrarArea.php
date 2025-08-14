<?php
require 'conexao.php';
session_start();

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodo = $_POST['periodoEscondido'] ?? null;


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
        $sql = "UPDATE area SET nome=?, qtdPes=?, hectares=?, dataInicio=?, dataFim=?, variedades=?, produtos=?, pragasDoencas=?, agrotoxicos=?, mediaFolhas=?, colheitas=? 
                WHERE idarea=?";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param(
            "sissssssiiii",
            $_POST['nome'],
            $_POST['qtdPes'],
            $_POST['hectares'],
            $_POST['dataInicio'],
            $_POST['dataFim'],
            $_POST['variedades'],
            $_POST['produtos'],
            $_POST['pragasDoencas'],
            $_POST['agrotoxicos'],
            $_POST['mediaFolhas'],
            $_POST['colheitas'],
            $idarea
        );
        $acaoMsg = "atualizada";
    } else {
        // Cadastra área
        $sql = "INSERT INTO area (nome, qtdPes, hectares, dataInicio, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param(
            "sissssssiiii",
            $_POST['nome'],
            $_POST['qtdPes'],
            $_POST['hectares'],
            $_POST['dataInicio'],
            $_POST['dataFim'],
            $_POST['variedades'],
            $_POST['produtos'],
            $_POST['pragasDoencas'],
            $_POST['agrotoxicos'],
            $_POST['mediaFolhas'],
            $_POST['colheitas'],
            $idtabaco
        );
        $acaoMsg = "cadastrada";
    }

    if ($stmt->execute()) {
        $msg = "Área {$acaoMsg} com sucesso.";
    } else {
        $msg = "Erro ao salvar a área.";
    }

    header("Location: tabaco.php?mensagem=" . urlencode($msg));
    exit;
}

if (isset($_POST['excluir']) && !empty($_POST['idarea'])) {
    $stmt = $conecta->prepare("DELETE FROM area WHERE idarea = ?");
    $stmt->bind_param("i", $_POST['idarea']);
    $stmt->execute();

    $msg = 'Área excluída com sucesso.';
    header("Location: tabaco.php?mensagem=" . urlencode($msg));
    exit;
}
