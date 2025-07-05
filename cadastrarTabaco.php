<?php 
    require 'conexao.php';


// VERIFICA ENVIO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $safra_id = $_POST['safra_id'] ?? '';
    $periodo = $_POST['periodo'];
    $total_plantado = $_POST['total_plantado'];
    $quilos_produzidos = $_POST['quilos_produzidos'];
    $quantidade_estufadas = $_POST['quantidade_estufadas'];
    $total_hectares = $_POST['total_hectares'];

    // INSERIR ou ATUALIZAR SAFRA
    if ($safra_id) {
        $stmt = $conn->prepare("UPDATE safras SET periodo=?, total_plantado=?, quilos_produzidos=?, quantidade_estufadas=?, total_hectares=? WHERE id=?");
        $stmt->bind_param("sssssi", $periodo, $total_plantado, $quilos_produzidos, $quantidade_estufadas, $total_hectares, $safra_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO safras (periodo, total_plantado, quilos_produzidos, quantidade_estufadas, total_hectares) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $periodo, $total_plantado, $quilos_produzidos, $quantidade_estufadas, $total_hectares);
        $stmt->execute();
        $safra_id = $stmt->insert_id;
    }

    // INSERIR/ATUALIZAR ÁREAS
    $area_ids = $_POST['area_id'] ?? [];
    foreach ($area_ids as $i => $area_id) {
        $total_plantado_area = $_POST['total_plantado_area'][$i];
        $total_hectares_area = $_POST['total_hectares_area'][$i];
        $data_plantio = $_POST['data_plantio'][$i];
        $data_fimColheita = $_POST['data_fimColheita'][$i];
        $variedades = $_POST['variedades'][$i];
        $produtos_utilizados = $_POST['produtos_utilizados'][$i];
        $pragas_doencas = $_POST['pragas_doencas'][$i];
        $agrotoxicos_defensivos = $_POST['agrotoxicos_defensivos'][$i];
        $media_folhas = $_POST['media_folhas'][$i];
        $quantidade_colhida = $_POST['quantidade_colhida'][$i];

        if ($area_id) {
            // EDITA ÁREA
            $stmt2 = $conn->prepare("UPDATE areas_safra SET 
                total_plantado_area=?, total_hectares_area=?, data_plantio=?, data_fim_colheita=?, 
                variedades=?, produtos_utilizados=?, pragas_doencas=?, agrotoxicos_defensivos=?, 
                media_folhas=?, quantidade_colhida=?
                WHERE id=? AND safra_id=?");
            $stmt2->bind_param("ssssssssssii", $total_plantado_area, $total_hectares_area, $data_plantio, $data_fimColheita,
                $variedades, $produtos_utilizados, $pragas_doencas, $agrotoxicos_defensivos,
                $media_folhas, $quantidade_colhida, $area_id, $safra_id);
            $stmt2->execute();
        } else {
            // INSERE NOVA ÁREA
            $stmt2 = $conn->prepare("INSERT INTO areas_safra (
                safra_id, total_plantado_area, total_hectares_area, data_plantio,
                data_fim_colheita, variedades, produtos_utilizados, pragas_doencas,
                agrotoxicos_defensivos, media_folhas, quantidade_colhida
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("issssssssss", $safra_id, $total_plantado_area, $total_hectares_area, $data_plantio,
                $data_fimColheita, $variedades, $produtos_utilizados, $pragas_doencas,
                $agrotoxicos_defensivos, $media_folhas, $quantidade_colhida);
            $stmt2->execute();
        }
    }

    echo "Dados salvos com sucesso!";
}

?>
