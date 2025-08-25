//lista de tarefas//

let tasks = [];
let taskId = 1;
let editingTaskId = null;


function addTask() {
    const taskInput = document.getElementById("taskInput");
    const taskText = taskInput.value.trim();
    if (taskText === "") return;
    const task = {
        id: taskId++, text: taskText, completed: false
    }
    tasks.push(task);
    updateTaskList();
    taskInput.value = "";
}


function updateTaskList() {

    const taskList = document.getElementById("taskList");

    taskList.innerHTML = "";

    tasks.forEach(task => {
        const li = document.createElement("li");
        li.classList.add("task-item");
        // Verifica se a tarefa está em edição para definir o conteúdo do span como um input
        const taskContent = editingTaskId === task.id ? `<input type="text" id="editingTaskInput" value="${task.text}">` : `<span class="${task.completed ? "completed-task" : ""}"> ${task.text}</span`;
        editIcon = editingTaskId === task.id ? "&#10004;" : "&#9998;"; // Altera o icone do botão de acordo com o estado de edição
        const completionIcon = task.completed ? "&#10060;" : "&#10004;"; // Altera o ícone do botão de acordo com o estado de conclusão
        li.innerHTML = `
    ${taskContent}
    <span>
    <button onclick="editTask(${task.id})">${editIcon}</button>
    <button onclick="deleteTask(${task.id})">&#128465;</button>
    <button onclick="toggleTaskCompletion(${task.id})">${completionIcon}</button>
    </span>
    `;
        taskList.appendChild(li);
    });

    updateProgress();
}


function updateProgress() {
    const totalTasks = tasks.length;
    const completedTasks = tasks.filter(task => task.completed).length;
    const progressPercent = (completedTasks / totalTasks) * 100;
    const progressBar = document.getElementById("progressBar");
    progressBar.style.width = progressPercent + "%";
}


function toggleTaskCompletion(id) {
    //const task = tasks.find(task => task.id = id);
    var task = null;
    var ind = 0;
    for(i = 0; i < tasks.length; i++){
        if(tasks[i].id == id){
            task = tasks[i];
            ind = i;
            break;
        }
    }

    if (task) {
        task.completed = !task.completed;
        tasks[ind] = task;
        updateTaskList();
    }
}


function editTask(id) {
    const task = tasks.find(task => task.id === id);
    if (task) {
        // Verifica se a tarefa já está em edição para salvar as alterações
        if (editingTaskId === task.id) {
            const editingTaskInput = document.getElementById("editingTaskInput");
            const newText = editingTaskInput.value.trim();
            if (newText !== "") {
                task.text = newText;
                editingTaskId = null; // sai do modo de edição
                updateTaskList();
            }
        } else {
            editingTaskId = task.id; // Entra no modo de edição
            updateTaskList();
        }
    }
}
function deleteTask(id) {

    tasks = tasks.filter(task => task.id !== id);

    updateTaskList();
}
// --------------------- SALDO ---------------------

let registros = [];
//const produtorId = 1; 

function formatarMoeda(valor) {
  return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}

async function carregarRegistros() {
  const res = await fetch(`saldo_crud.php?acao=listar&produtor_id=${produtorId}`);
  registros = await res.json();
  atualizarSaldos();
  exibirRegistros();
}

function atualizarSaldos() {
  let saldoEucalipto = 0;
  let saldoTabaco = 0;

  registros.forEach(r => {
    const valor = r.sinal === '+' || r.tipo === 'positivo' ? r.valor : -r.valor;

    if (r.culturas === 'eucalipto' || r.cultura === 'eucalipto') {
      saldoEucalipto += valor;
    } else if (r.culturas === 'tabaco' || r.cultura === 'tabaco') {
      saldoTabaco += valor;
    } else if (r.culturas === 'ambos' || r.cultura === 'ambos') {
      saldoEucalipto += valor / 2;
      saldoTabaco += valor / 2;
    }
  });

  const saldoTotal = saldoEucalipto + saldoTabaco;

  document.getElementById('saldo-valor').textContent = formatarMoeda(saldoTotal);
  document.getElementById('saldo-eucalipto').textContent = formatarMoeda(saldoEucalipto);
  document.getElementById('saldo-tabaco').textContent = formatarMoeda(saldoTabaco);
}

async function adicionarItemRegistro() {
  const valor = parseFloat(document.getElementById('input-valor').value);
  const tipo = document.getElementById('input-tipo').value;
  const descricao = document.getElementById('input-descricao').value.trim();
  const data = document.getElementById('input-data').value;
  const cultura = document.getElementById('input-cultura').value;
  const categoria = document.getElementById('input-categoria').value;

  if (isNaN(valor) || descricao === "" || data === "" || !cultura) {
    alert("Por favor, preencha todos os campos corretamente.");
    return;
  }

  const formData = new FormData();
  formData.append("acao", "criar");
  formData.append("valor", valor);
  formData.append("sinal", tipo === "positivo" ? "+" : "-");
  formData.append("descricao", descricao);
  formData.append("data", data);
  formData.append("cultura", cultura);
  formData.append("seletor", categoria);
  formData.append("produtor_id", produtorId);

  const res = await fetch("saldo_crud.php", { method: "POST", body: formData });
  const dados = await res.json();
  exibirRegistros()


  if (dados.sucesso) {
    
    document.getElementById('input-valor').value = "";
    document.getElementById('input-tipo').value = "positivo";
    document.getElementById('input-descricao').value = "";
    document.getElementById('input-data').value = "";
    document.getElementById('input-cultura').value = "ambos";
    document.getElementById('input-categoria').value = "insumos";
    
    await carregarRegistros(); 

    document.getElementById('filtro-data-inicio').value = "";
    document.getElementById('filtro-data-fim').value = "";
    document.getElementById('filtro-cultura').value = "todos";
    exibirRegistros();
    
  } else {
    alert("Erro ao adicionar registro");
  }
  carregarRegistros();
 
}

function exibirRegistros(filtrados = null) {
  const lista = document.getElementById('lista-registro');
  lista.innerHTML = "";

  const listaFinal = filtrados || registros;

  if (listaFinal.length === 0) {
    lista.innerHTML = "<li>Nenhum registro encontrado.</li>";
    return;
  }

  listaFinal.slice().reverse().forEach(registro => {
    const li = document.createElement('li');
    li.classList.add(registro.sinal === '+' || registro.tipo === 'positivo' ? 'registro-positivo' : 'registro-negativo');

    const detalhes = document.createElement('div');
    detalhes.className = 'detalhes-registro';
    detalhes.innerHTML = `
      <span><strong>${registro.sinal || (registro.tipo === 'positivo' ? '+' : '-')} ${formatarMoeda(Math.abs(registro.valor))}</strong> - ${registro.descricao} (${registro.culturas || registro.cultura})</span>
      <small>${registro.dataOperacao || registro.data}</small>
    `;

    const botaoExcluir = document.createElement('button');
    botaoExcluir.textContent = '🗑️';
    botaoExcluir.classList.add('botao-excluir');
    botaoExcluir.onclick = () => excluirRegistro(registro.idsaldo);

    li.appendChild(detalhes);
    li.appendChild(botaoExcluir);
    lista.appendChild(li);
  });
}

async function excluirRegistro(id) {
  if (!confirm("Deseja realmente excluir este registro?")) return;

  const formData = new FormData();
  formData.append("acao", "deletar");
  formData.append("id", id);

  const res = await fetch("saldo_crud.php", { method: "POST", body: formData });
  const dados = await res.json();
  exibirRegistros();

  if (dados.sucesso) {
    carregarRegistros();
    document.getElementById('filtro-data-inicio').value = "";
    document.getElementById('filtro-data-fim').value = "";
    document.getElementById('filtro-cultura').value = "todos";
    exibirRegistros();
  } else {
    alert("Erro ao excluir registro");
  }


}

// Carregar ao abrir a página
document.addEventListener("DOMContentLoaded", carregarRegistros);


function filtrarPorData() {
  const dataInicio = document.getElementById('filtro-data-inicio').value;
  const dataFim = document.getElementById('filtro-data-fim').value;
  const culturaFiltro = document.getElementById('filtro-cultura').value;

  let filtrados = registros.filter(r => {
    const dataRegistro = new Date(r.data);
    const dentroDoPeriodo =
      (!dataInicio || new Date(dataInicio) <= dataRegistro) &&
      (!dataFim || new Date(dataFim) >= dataRegistro);

    const culturaCondicional =
      culturaFiltro === "todos" ||
      r.cultura === culturaFiltro ||
      (culturaFiltro === "ambos" && r.cultura === "ambos");

    return dentroDoPeriodo && culturaCondicional;
  });

  exibirRegistros(filtrados);
}

function limparFiltro() {
  document.getElementById('filtro-data-inicio').value = "";
  document.getElementById('filtro-data-fim').value = "";
  document.getElementById('filtro-cultura').value = "todos";
  exibirRegistros();
}

atualizarSaldos();
//filtrarPorData();







document.addEventListener("DOMContentLoaded", () => {
  const tabela = document.querySelector("#tabela-transacoes tbody");
  const btnAdicionar = document.getElementById("btn-adicionar");

  // Função para carregar lista
  function carregarTransacoes() {
    fetch("saldo_crud.php?acao=ler")
      .then(r => r.json())
      .then(dados => {
        tabela.innerHTML = "";
        dados.forEach(transacao => {
          let tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${transacao.idtransacao}</td>
            <td>${transacao.valor}</td>
            <td>${transacao.sinal}</td>
            <td>${transacao.descricao}</td>
            <td>${transacao.dataOperacao}</td>
            <td>${transacao.culturas}</td>
            <td>${transacao.seletor}</td>
            <td>
              <button onclick="editarTransacao(${transacao.idtransacao})">Editar</button>
              <button onclick="deletarTransacao(${transacao.idtransacao})">Excluir</button>
            </td>
          `;
          tabela.appendChild(tr);
        });
      });
  }

  // Adicionar transação
  btnAdicionar.addEventListener("click", () => {
    const dados = new FormData();
    dados.append("acao", "criar");
    dados.append("produtor_id", document.getElementById("input-produtor-id").value);
    dados.append("valor", document.getElementById("input-valor").value);
    dados.append("sinal", document.getElementById("input-sinal").value);
    dados.append("descricao", document.getElementById("input-descricao").value);
    dados.append("data", document.getElementById("input-data").value);
    dados.append("cultura", document.getElementById("input-cultura").value);
    dados.append("seletor", document.getElementById("input-seletor").value);

    fetch("saldo_crud.php", { method: "POST", body: dados })
      .then(r => r.json())
      .then(res => {
        if (res.sucesso) {
          alert("Transação adicionada!");
          carregarTransacoes();
        } else {
          alert("Erro ao adicionar.");
        }
      });
  });

  // Deletar transação
  window.deletarTransacao = (id) => {
    if (!confirm("Tem certeza que deseja excluir?")) return;
    const dados = new FormData();
    dados.append("acao", "deletar");
    dados.append("id", id);

    fetch("saldo_crud.php", { method: "POST", body: dados })
      .then(r => r.json())
      .then(res => {
        if (res.sucesso) {
          carregarTransacoes();
        } else {
          alert("Erro ao excluir.");
        }
      });
  };

  carregarTransacoes();
});
