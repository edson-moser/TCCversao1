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

// inicializa
document.addEventListener('DOMContentLoaded', () => {
  carregarEucalipto();
  // vincula submit (caso não esteja inline)
  document.getElementById('formEuca').addEventListener('submit', handleSubmit);
});