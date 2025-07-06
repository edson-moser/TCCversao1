<?php 
    require 'conexao.php';


// VERIFICA ENVIO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabaco_id = $_POST['tabaco_idtabaco'] ?? '';
    $periodo = $_POST['periodoSafra'];
    $total_plantado = $_POST['total'];
    $quilos_produzidos = $_POST['kilos'];
    $quantidade_estufadas = $_POST['estufadas'];
    $total_hectares = $_POST['totalHectares'];

    // INSERIR ou ATUALIZAR SAFRA
    if ($tabaco_id) {
        $stmt = $conn->prepare("UPDATE tabaco SET periodoSafra=?, total=?, kilos=?, estufadas=?, totalHectares=? WHERE id=?");
        $stmt->bind_param("sssssi", $periodo, $total_plantado, $quilos_produzidos, $quantidade_estufadas, $total_hectares, $tabaco_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO tabaco (periodoSafra, total, kilos, estufadas, totalHectares) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $periodo, $total_plantado, $quilos_produzidos, $quantidade_estufadas, $total_hectares);
        $stmt->execute();
        $tabaco_id = $stmt->insert_id; // Agora recebe o novo ID corretamente
    }

    // INSERIR/ATUALIZAR ÁREAS
    $area_ids = $_POST['idarea'] ?? [];
    foreach ($area_ids as $i => $area_id) {
        $nomeArea = $_POST['nome'][$i];
        $total_plantado_area = $_POST['qtdPes'][$i];
        $total_hectares_area = $_POST['hectares'][$i];
        $data_plantio = $_POST['dataInicio'][$i];
        $data_fimColheita = $_POST['dataFim'][$i];
        $variedades = $_POST['variedades'][$i];
        $produtos_utilizados = $_POST['produtos'][$i];
        $pragas_doencas = $_POST['pragasDoencas'][$i];
        $agrotoxicos_defensivos = $_POST['agrotoxicos'][$i];
        $media_folhas = $_POST['mediaFolhas'][$i];
        $quantidade_colhida = $_POST['colheitas'][$i];

        if ($area_id) {
            // EDITA ÁREA
            $stmt2 = $conn->prepare("UPDATE area SET 
                nomeArea=?, qtdPes=?, hectares=?, dataInicio=?, dataFim=?, 
                variedades=?, produtos=?, pragasDoencas=?, agrotoxicos=?, 
                mediaFolhas=?, colheitas=?
                WHERE idarea=? AND tabaco_idtabaco=?");
            $stmt2->bind_param("ssssssssssii", $nomeArea, $total_plantado_area, $total_hectares_area, $data_plantio, $data_fimColheita,
                $variedades, $produtos_utilizados, $pragas_doencas, $agrotoxicos_defensivos,
                $media_folhas, $quantidade_colhida, $area_id, $tabaco_id);
            $stmt2->execute();
        } else {
            // INSERE NOVA ÁREA
            $stmt2 = $conn->prepare("INSERT INTO area (
                tabaco_idtabaco, nomeArea, qtdPes, hectares, dataInicio, dataFim, 
                variedades, produtos, pragasDoencas, agrotoxicos, 
                mediaFolhas, colheitas
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssssssssss", $tabaco_id, $nomeArea, $total_plantado_area, $total_hectares_area, $data_plantio,
                $data_fimColheita, $variedades, $produtos_utilizados, $pragas_doencas,
                $agrotoxicos_defensivos, $media_folhas, $quantidade_colhida);
            $stmt2->execute();
        }
    }

    echo "Dados salvos com sucesso!";
}


?>
