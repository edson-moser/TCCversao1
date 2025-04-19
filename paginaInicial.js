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


//saldo
let saldoAtual = 0;
let registros = [];

function formatarMoeda(valor) {
  return `R$ ${valor.toFixed(2).replace('.', ',')}`;
}

function atualizarSaldo() {
  const saldoElemento = document.getElementById('saldo-valor');
  saldoElemento.textContent = formatarMoeda(saldoAtual);
}

function adicionarItemRegistro() {
  const valorInput = document.getElementById('input-valor');
  const tipoInput = document.getElementById('input-tipo');
  const descricaoInput = document.getElementById('input-descricao');
  const dataInput = document.getElementById('input-data');
  const valor = parseFloat(valorInput.value);
  const tipo = tipoInput.value;
  const descricao = descricaoInput.value.trim();
  const data = dataInput.value;

  if (isNaN(valor) || descricao === "" || data === "") {
    alert("Por favor, preencha todos os campos corretamente.");
    return;
  }

  const registro = {
    id: Date.now(),
    valor,
    tipo,
    descricao,
    data
  };

  registros.push(registro);

  if (tipo === 'positivo') {
    saldoAtual += valor;
  } else {
    saldoAtual -= valor;
  }

  atualizarSaldo();
  exibirRegistros();

  valorInput.value = '';
  descricaoInput.value = '';
  dataInput.value = '';
  tipoInput.value = 'positivo';
}

function exibirRegistros(filtro = null) {
  const lista = document.getElementById('lista-registro');
  lista.innerHTML = "";

  const registrosFiltrados = filtro
    ? registros.filter(r => r.data === filtro)
    : registros;

  registrosFiltrados.slice().reverse().forEach(registro => {
    const li = document.createElement('li');
    li.classList.add(registro.tipo === 'positivo' ? 'registro-positivo' : 'registro-negativo');

    const detalhes = document.createElement('div');
    detalhes.className = 'detalhes-registro';
    detalhes.innerHTML = `
      <span><strong>${registro.tipo === 'positivo' ? '+' : '-'} ${formatarMoeda(Math.abs(registro.valor))}</strong> - ${registro.descricao}</span>
      <small>${registro.data}</small>
    `;

    const botaoExcluir = document.createElement('button');
    botaoExcluir.textContent = '🗑️';
    botaoExcluir.classList.add('botao-excluir');
    botaoExcluir.onclick = () => excluirRegistro(registro.id);

    li.appendChild(detalhes);
    li.appendChild(botaoExcluir);
    lista.appendChild(li);
  });
}

function excluirRegistro(id) {
  const registro = registros.find(r => r.id === id);
  if (!registro) return;

  if (registro.tipo === 'positivo') {
    saldoAtual -= registro.valor;
  } else {
    saldoAtual += registro.valor;
  }

  registros = registros.filter(r => r.id !== id);
  atualizarSaldo();
  exibirRegistros(document.getElementById('filtro-data-input').value || null);
}

function filtrarPorData() {
  const dataSelecionada = document.getElementById('filtro-data-input').value;
  exibirRegistros(dataSelecionada || null);
}

function limparFiltro() {
  document.getElementById('filtro-data-input').value = "";
  exibirRegistros();
}

