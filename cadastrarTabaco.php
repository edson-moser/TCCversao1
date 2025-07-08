<?php
require 'conexao.php';
include('protect.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodo = $_POST['periodoSafra'] ?? '';
    $total_plantado = floatval($_POST['total'] ?? 0);
    $precoTotal = floatval($_POST['precoTotal'] ?? 0);
    $kilos = floatval($_POST['kilos'] ?? 0);
    $estufadas = intval($_POST['estufadas'] ?? 0);
    $hectares = floatval($_POST['totalHectares'] ?? 0);

    // Verifica se já existe esse período
    $sql = "SELECT idtabaco FROM tabaco WHERE periodoSafra = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("si", $periodo, $produtor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Já existe, faz UPDATE
        $row = $result->fetch_assoc();
        $tabaco_id = $row['idtabaco'];

        $sql = "UPDATE tabaco SET total = ?, estufadas = ?, kilos = ?, totalHectares = ?, precoTotal = ?
                WHERE idtabaco = ? AND produtor_idprodutor = ?";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("iiddsii", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $tabaco_id, $produtor_id);
        if ($stmt->execute()) {
            header('Location: tabaco.php');
        } else {
            echo "<p style='color:red;'>Erro ao atualizar.</p>";
        }
    } else {
        // Não existe, faz INSERT
        $sql = "INSERT INTO tabaco (total, estufadas, kilos, totalHectares, precoTotal, produtor_idprodutor, periodoSafra)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("iidddis", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $produtor_id, $periodo);
        if ($stmt->execute()) {
            header('Location: tabaco.php');
        } else {
            echo "<p style='color:red;'>Erro ao salvar.</p>";
        }
    }
}
?>
