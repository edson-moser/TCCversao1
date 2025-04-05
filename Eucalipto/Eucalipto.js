function changeType(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);
    const selectedType = select.value;  // Obtém o valor selecionado no dropdown
  
    // Atualiza o tipo do input de acordo com a opção escolhida
    input.type = selectedType;
  }

  var ctx = document.getElementsByClassName("line-chart")

  var chartGrafico = new Chart(ctx, {
    type: 'line'
  })