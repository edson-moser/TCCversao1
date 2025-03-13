

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
    const task = tasks.find(task => task.id = id);
    if (task) {
        task.completed = !task.completed;
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

