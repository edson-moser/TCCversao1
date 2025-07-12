<?php
require 'conexao.php';
session_start();

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodo = $_POST['periodoEscondido'] ?? null;

// Buscar idtabaco da safra
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
    $idarea = $_POST['idarea'] ?? null;
    $sql = $idarea
        ? "UPDATE area SET nome=?, qtdPes=?, hectares=?, dataInicio=?, dataFim=?, variedades=?, produtos=?, pragasDoencas=?, agrotoxicos=?, mediaFolhas=?, colheitas=? WHERE idarea=?"
        : "INSERT INTO area (nome, qtdPes, hectares, dataInicio, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conecta->prepare($sql);
    if ($idarea) {
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
    } else {
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
    }

    if ($stmt->execute()) {
        $msg = $idarea ? 'Área atualizada com sucesso.' : 'Área cadastrada com sucesso.';
    } else {
        $msg = 'Erro ao salvar a área.';
    }

    header("Location: tabaco.php?mensagem=" . urlencode($msg));
    exit;
}

if (isset($_POST['excluir']) && $_POST['idarea']) {
    $stmt = $conecta->prepare("DELETE FROM area WHERE idarea = ?");
    $stmt->bind_param("i", $_POST['idarea']);
    $stmt->execute();

    $msg = 'Área excluída com sucesso.';
    header("Location: tabaco.php?mensagem=" . urlencode($msg));
    exit;
}
