$("#btnCarregar").click(function(){
    let periodo = $("#periodoSafra").val();

    $.ajax({
        url: "ajaxPeriodo.php",
        method: "POST",
        data: { periodoSelecionado: periodo },
        success: function(result){
            $("#selectAreas").html(
                '<select name="idareaSelecionada" class="form-control">' +
                '<option value="">Nova área:</option>' +
                result +
                '</select>'
            );
        }
    });
});
