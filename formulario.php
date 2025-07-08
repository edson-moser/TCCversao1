<?php
require 'conexao.php';
  $total = 0;
    $estufadas = 0;
    $kilos = 0;
    $precoTotal = 0;
    $totalHectares = 0;
    $periodoSafra = 0;

$sqlTabaco = "SELECT * FROM tabaco WHERE idtabaco = ?";
$stmt = $conn->prepare($sqlTabaco);
$stmt->bind_param("i", $idtabaco);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tabaco = $result->fetch_assoc();
    $total = $tabaco['total'];
    $estufadas = $tabaco['estufadas'];
    $kilos = $tabaco['kilos'];
    $precoTotal = $tabaco['precoTotal'];
    $totalHectares = $tabaco['totalHectares'];
    $periodoSafra = $tabaco['periodoSafra'];
} else {
    echo "Safra não encontrada.<br>";
}

$sqlArea = "SELECT * FROM area WHERE tabaco_idtabaco = ?";
$stmt2 = $conn->prepare($sqlArea);
$stmt2->bind_param("i", $idtabaco);
$stmt2->execute();
$resultArea = $stmt2->get_result();

if ($result->num_rows > 0) {
    while ($linha = $result->fetch_assoc()) {
        // Cada linha será um array com os dados de uma área
        $areas[] = [
            'nomeArea' => $linha['nomeArea'],
            'qtdPes' => $linha['qtdPes'],
            'hectares' => $linha['hectares'],
            'dataInicio' => $linha['dataInicio'],
            'dataFim' => $linha['dataFim'],
            'variedades' => $linha['variedades'],
            'produtos' => $linha['produtos'],
            'pragasDoencas' => $linha['pragasDoencas'],
            'agrotoxicos' => $linha['agrotoxicos'],
            'mediaFolhas' => $linha['mediaFolhas'],
            'colheitas' => $linha['colheitas'],
        ];
    }
}
$stmt->close();
$stmt2->close();
$conn->close();

?>