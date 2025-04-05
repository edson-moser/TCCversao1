function changeType(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);
    const selectedType = select.value;  // Obtém o valor selecionado no dropdown
  
    // Atualiza o tipo do input de acordo com a opção escolhida
    input.type = selectedType;
  }

  var ctx = document.getElementsByClassName("line-chart")

  const meuGrafico = new Chart(ctx, {
    type: 'bar', // tipos: 'bar', 'line', 'pie', etc.
    data: {
      labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
      datasets: [{
        label: 'Quantidades de pés plantados',
        data: [50000, 19000, 30000, 22000, 43000, 67000],
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });