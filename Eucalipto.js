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


  const endpointEuca = 'eucalipto_crud.php';

async function carregarEucalipto() {
  if (typeof produtorId === 'undefined' || produtorId === null) {
    console.warn('produtorId não definido. Pulando chamada que requer produtorId.');
    return;
}

  try {
    const res = await fetch(`${endpointEuca}?acao=listar&produtor_id=${produtorId}`, {cache: 'no-store'});
    if (!res.ok) throw new Error('Erro na requisição: ' + res.status);
    const dados = await res.json();
    preencherTabelaEuca(dados);
  } catch (err) {
    console.error(err);
    alert('Erro ao carregar registros. Veja console.');
  }
}

function preencherTabelaEuca(lista) {
  const tbody = document.querySelector('#tabelaEuca tbody');
  tbody.innerHTML = '';
  if (!Array.isArray(lista) || lista.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7">Nenhum registro encontrado.</td></tr>';
    return;
  }

  lista.forEach(item => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.ideucalipto}</td>
      <td>${item.area ?? ''}</td>
      <td>${item.qtdEucalipto ?? ''}</td>
      <td>${item.dataPlantio ?? ''}</td>
      <td>${item.dataCorte ?? ''}</td>
      <td>${item.produtor_idprodutor ?? ''}</td>
      <td>
        <button class="btn btn-sm btn-primary" onclick="editarEuca(${item.ideucalipto})">Editar</button>
        <button class="btn btn-sm btn-danger" onclick="deletarEuca(${item.ideucalipto})">Excluir</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function preencherForm(item) {
  document.getElementById('ideucalipto').value = item.ideucalipto || '';
  document.getElementById('area').value = item.area || '';
  document.getElementById('qtdEucalipto').value = item.qtdEucalipto || '';
  document.getElementById('dataPlantio').value = item.dataPlantio || '';
  document.getElementById('dataCorte').value = item.dataCorte || '';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function editarEuca(id) {
  try {
    // busca lista e encontra o item (simplificação)
    const res = await fetch(`${endpointEuca}?acao=listar&produtor_id=${produtorId}`, {cache:'no-store'});
    const dados = await res.json();
    const item = dados.find(i => Number(i.ideucalipto) === Number(id));
    if (item) preencherForm(item);
    else alert('Registro não encontrado para edição.');
  } catch (err) {
    console.error(err);
    alert('Erro ao buscar registro para editar.');
  }
}

function limparForm() {
  document.getElementById('formEuca').reset();
  document.getElementById('ideucalipto').value = '';
}

async function handleSubmit(e) {
  e.preventDefault();
  const id = document.getElementById('ideucalipto').value;
  const formEl = document.getElementById('formEuca');
  const form = new FormData(formEl);
  const acao = id ? 'editar' : 'criar';
  form.append('acao', acao);
  if (!form.has('produtor_id')) form.append('produtor_id', produtorId);

  try {
    const res = await fetch(endpointEuca, { method: 'POST', body: form });
    const json = await res.json();
    if (json.sucesso) {
      alert('Operação realizada com sucesso.');
      limparForm();
      await carregarEucalipto();
    } else {
      console.error(json);
      alert('Erro: ' + (json.erro || 'Problema desconhecido'));
    }
  } catch (err) {
    console.error(err);
    alert('Erro ao salvar. Veja console.');
  }
}

async function deletarEuca(id) {
  if (!confirm('Deseja realmente excluir este registro?')) return;
  const form = new FormData();
  form.append('acao', 'deletar');
  form.append('id', id);

  try {
    const res = await fetch(endpointEuca, { method: 'POST', body: form });
    const json = await res.json();
    if (json.sucesso) {
      alert('Registro excluído.');
      await carregarEucalipto();
    } else {
      console.error(json);
      alert('Erro ao excluir: ' + (json.erro || 'Problema desconhecido'));
    }
  } catch (err) {
    console.error(err);
    alert('Erro ao excluir. Veja console.');
  }
}

// exemplo: proteger seleção de elemento antes de registrar listener
const meuBotao = document.getElementById('idDoBotaoQueUsa'); // substitua pelo id real
if (meuBotao) {
    meuBotao.addEventListener('click', function (e) {
        // ...existing code...
    });
} else {
    console.warn('Elemento #idDoBotaoQueUsa não encontrado — listener não registrado.');
}

// se houver código no final que adiciona listeners em DOMContentLoaded:
document.addEventListener('DOMContentLoaded', function () {
    // verifique cada elemento antes de usar addEventListener
    const elemento = document.getElementById('algumId');
    if (elemento) {
        elemento.addEventListener('change', /*...*/);
    } else {
        console.warn('Elemento #algumId não encontrado no DOM.');
    }

    // chama carregarEucalipto() seguramente
    carregarEucalipto();
});

// inicializa
document.addEventListener('DOMContentLoaded', () => {
  carregarEucalipto();
  // vincula submit (caso não esteja inline)
  document.getElementById('formEuca').addEventListener('submit', handleSubmit);
});

// Gráfico e lógica de interação para Eucalipto
(function () {
  const ctx = document.getElementById('eucaliptoChart');
  if (!ctx) {
    console.warn('Canvas #eucaliptoChart não encontrado.');
    return;
  }

  const config = {
    type: 'line',
    data: {
      labels: [], // datas (strings yyyy-mm-dd)
      datasets: [
        {
          label: 'Produção (pés)',
          data: [],
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: false,
          tension: 0.1,
          pointRadius: 5
        },
        {
          label: 'Pré-visualização',
          data: [],
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          fill: false,
          tension: 0.1,
          pointRadius: 6,
          borderDash: [6, 4]
        }
      ]
    },
    options: {
      scales: {
        x: {
          type: 'category',
          title: { display: true, text: 'Data de plantio' }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Quantidade de pés' }
        }
      }
    }
  };

  const chart = new Chart(ctx, config);

  function formatDateInputValue(d) {
    // espera string yyyy-mm-dd -> retorna mesma string ou today se vazio
    if (!d) {
      const t = new Date();
      const yyyy = t.getFullYear();
      const mm = String(t.getMonth() + 1).padStart(2, '0');
      const dd = String(t.getDate()).padStart(2, '0');
      return `${yyyy}-${mm}-${dd}`;
    }
    return d;
  }

  function setPreview(dateStr, value) {
    const previewDataset = chart.data.datasets[1];
    // limpar preview anterior
    previewDataset.data = [];
    // se valor válido, adiciona preview
    if (dateStr && value !== '' && !isNaN(Number(value))) {
      // se a data já existe nas labels (ponto definitivo), mostrar preview mas sem duplicar label:
      const labels = chart.data.labels;
      const idx = labels.indexOf(dateStr);
      if (idx === -1) {
        // adicionar temporariamente a label para preview
        chart.data.labels = [...labels, dateStr];
        previewDataset.data = [...Array(labels.length).fill(null), Number(value)];
      } else {
        // existe ponto definitivo nessa data — sobrescrever preview no mesmo índice (não adiciona label)
        previewDataset.data = Array(chart.data.labels.length).fill(null);
        previewDataset.data[idx] = Number(value);
      }
    } else {
      // se não tem preview, manter dataset vazio and restore labels if preview added earlier
      // remover quaisquer labels sem dados (edge-case simple approach: do nothing)
    }
    chart.update();
  }

  function addPoint(dateStr, value) {
    if (!dateStr || value === '' || isNaN(Number(value))) return false;
    const val = Number(value);
    const labels = chart.data.labels;
    const idx = labels.indexOf(dateStr);
    if (idx === -1) {
      chart.data.labels.push(dateStr);
      chart.data.datasets[0].data.push(val);
    } else {
      // soma ou substitui? aqui substituímos o valor existente
      chart.data.datasets[0].data[idx] = val;
    }
    // limpar preview
    chart.data.datasets[1].data = Array(chart.data.labels.length).fill(null);
    chart.update();
    return true;
  }

  document.addEventListener('DOMContentLoaded', function () {
    const inputQuant = document.getElementById('input1');
    const inputDate = document.getElementById('inputDate');
    const form = document.getElementById('eucalipto-form');

    if (!inputQuant) console.warn('#input1 não encontrado');
    if (!inputDate) console.warn('#inputDate não encontrado');
    if (!form) console.warn('#eucalipto-form não encontrado');

    // atualização em tempo real enquanto digita (pré-visualização)
    if (inputQuant) {
      inputQuant.addEventListener('input', function () {
        const dateVal = formatDateInputValue(inputDate ? inputDate.value : '');
        setPreview(dateVal, inputQuant.value);
      });
    }

    // atualiza preview também quando muda a data
    if (inputDate) {
      inputDate.addEventListener('change', function () {
        const dateVal = formatDateInputValue(inputDate.value);
        setPreview(dateVal, inputQuant ? inputQuant.value : '');
      });
    }

    // tratar submit: fixa o ponto no gráfico
    if (form) {
      form.addEventListener('submit', function (ev) {
        ev.preventDefault();
        const dateVal = formatDateInputValue(inputDate ? inputDate.value : '');
        const success = addPoint(dateVal, inputQuant ? inputQuant.value : '');
        if (success) {
          // opcional: limpar campo quantidade após confirmar
          if (inputQuant) inputQuant.value = '';
          // opcional: limpar preview
          setPreview('', '');
        } else {
          // feedback simples
          console.warn('Data inválida ou quantidade inválida. Confirme os valores.');
        }

        // aqui você pode enviar para o servidor via fetch se desejar salvar:
        // fetch('salva_eucalipto.php', { method: 'POST', body: new URLSearchParams({ produtorId, data: dateVal, quantidade: value }) })
      });
    }

    // chamar carregarEucalipto se existir e produtorId estiver definido (mantém compatibilidade com código existente)
    if (typeof carregarEucalipto === 'function') {
      try {
        if (typeof produtorId !== 'undefined' && produtorId !== null) {
          carregarEucalipto();
        } else {
          // pode ainda querer carregar dados públicos/locais
          carregarEucalipto();
        }
      } catch (e) {
        console.error('Erro em carregarEucalipto():', e);
      }
    }
  });
})();