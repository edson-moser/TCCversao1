<?php 
    require 'conexao.php';
include('protect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produtor_id = $_SESSION['idprodutor'] ?? null;

    // Pegando os outros dados normalmente
   // $periodo = $_POST['periodoSafra'] ?? '';
   // $total_plantado = $_POST['total'] ?? 0;
    //$precoTotal = $_POST['precoTotal'] ?? 0;
   // $quilos_produzidos = $_POST['kilos'] ?? 0;
   // $quantidade_estufadas = $_POST['estufadas'] ?? 0;
    //$total_hectares = $_POST['totalHectares'] ?? 0;

    // INSERIR NO BANCO
    //$sql = "INSERT INTO tabaco (total, estufadas, kilos, totalHectares, produtor_idprodutor, periodoSafra)
      //      VALUES (?, ?, ?, ?, ?, ?)";
    //$stmt = $conn->prepare($sql);
  //  $stmt->bind_param("iiidis", $total_plantado, $quantidade_estufadas, $quilos_produzidos, $total_hectares, $produtor_id, $periodo);
    //$stmt->execute();

    
    // INSERIR ou ATUALIZAR SAFRA
   // if ($tabaco_id) {
     //   $stmt = $conecta->prepare("UPDATE tabaco SET periodoSafra=?, total=?,precoTotal=?, kilos=?, estufadas=?, totalHectares=? WHERE id=?");
       // $stmt->bind_param("sssssi", $periodo, $total_plantado,$precoTotal, $quilos_produzidos, $quantidade_estufadas, $total_hectares, $tabaco_id);
       // $stmt->execute();
        
   // } else {
     //   $stmt = $conecta->prepare("INSERT INTO tabaco (periodoSafra, total,precoTotal, kilos, estufadas, totalHectares) VALUES (?, ?, ?, ?, ?,?)");
       // $stmt->bind_param("ssssss", $periodo, $total_plantado,$precoTotal, $quilos_produzidos, $quantidade_estufadas, $total_hectares);
        //$stmt->execute();
       // $tabaco_id = $stmt->insert_id;
   // }
//}



$dados = [
    'idtabaco' => '',
    'periodoSafra' => '',
    'total' => '',
    'precoTotal' => '',
    'kilos' => '',
    'estufadas' => '',
    'totalHectares' => ''
];

// Carrega os dados se for edição via ?id=...
if (isset($_GET['id'])) {
    $tabaco_id = $_GET['id'];
    $sql = "SELECT * FROM tabaco WHERE idtabaco = ? AND produtor_idprodutor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tabaco_id, $produtor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $dados = $result->fetch_assoc();
    } else {
        echo "Registro não encontrado.";
        exit;
    }
}

// Se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabaco_id = $_POST['tabaco_idtabaco'] ?? null;
    $periodo = $_POST['periodoSafra'] ?? '';
    $total_plantado = $_POST['total'] ?? 0;
    $precoTotal = $_POST['precoTotal'] ?? 0;
    $kilos = $_POST['kilos'] ?? 0;
    $estufadas = $_POST['estufadas'] ?? 0;
    $hectares = $_POST['totalHectares'] ?? 0;

    if ($tabaco_id) {
        // Atualiza
        $sql = "UPDATE tabaco SET total = ?, estufadas = ?, kilos = ?, totalHectares = ?, precoTotal = ?, periodoSafra = ?
                WHERE idtabaco = ? AND produtor_idprodutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiddssii", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $periodo, $tabaco_id, $produtor_id);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Registro atualizado com sucesso.</p>";
            // Atualiza os dados preenchidos no formulário
            $dados = [
                'idtabaco' => $tabaco_id,
                'periodoSafra' => $periodo,
                'total' => $total_plantado,
                'precoTotal' => $precoTotal,
                'kilos' => $kilos,
                'estufadas' => $estufadas,
                'totalHectares' => $hectares
            ];
        } else {
            echo "<p style='color:red;'>Erro ao atualizar.</p>";
        }
    } else {
        // Insere novo
        $sql = "INSERT INTO tabaco (total, estufadas, kilos, totalHectares, precoTotal, produtor_idprodutor, periodoSafra)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidddis", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $produtor_id, $periodo);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Novo registro cadastrado com sucesso.</p>";
            $novo_id = $stmt->insert_id;
            // Preenche o formulário com os dados recém-salvos
            $dados = [
                'idtabaco' => $novo_id,
                'periodoSafra' => $periodo,
                'total' => $total_plantado,
                'precoTotal' => $precoTotal,
                'kilos' => $kilos,
                'estufadas' => $estufadas,
                'totalHectares' => $hectares
            ];
        } else {
            echo "<p style='color:red;'>Erro ao salvar.</p>";
        }
    }
}
}
?>
?>
