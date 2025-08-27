function changeType(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);
    const selectedType = select.value;  
  
    
    input.type = selectedType;
  }

  var ctx = document.getElementsByClassName("line-chart")

  const meuGrafico = new Chart(ctx, {
    type: 'bar', 
    data: {
      labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
      datasets: [{
        label: 'Quantidades de pés plantados',
        data: [50000, 19000, 30000, 22000, 43000, 67000],
        backgroundColor: 'rgba(106, 228, 167, 0.5)',
        borderColor: 'rgba(33, 102, 67, 0.5)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      animation: {
        duration: 2000,  
        easing: 'easeOutBounce',  
        delay: function(context) {
          var delay = 0;
          if (context.type === 'data' && context.mode === 'default' && context.datasetIndex === 0) {
            delay = context.dataIndex * 200;  
          }
          return delay;
        }
      }
    }
  });