<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Minha To-Do List</h1>

        <form id="taskForm" method="POST">
            <input type="text" id="taskTitle" placeholder="Título da tarefa" required>
            <textarea id="taskDescription" placeholder="Descrição da tarefa"></textarea>
            <button type="submit">Adicionar Tarefa</button>
        </form>
        <div class="pagination">
            <button id="prevPage" disabled>Anterior</button>
            <span id="pageInfo">1</span>
            <button id="nextPage">Próxima</button>
        </div>
        <h2>Tarefas:</h2>
        <ul id="taskList"></ul>
    </div>

    <script>
        const taskForm = document.getElementById('taskForm');
        const taskList = document.getElementById('taskList');
        let currentPage = 1;
        const limit = 5;
        const pageInfo = document.getElementById('pageInfo');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const popover = document.createElement('div');
        
        popover.classList.add('popover');
        document.body.appendChild(popover);

        taskList.addEventListener('mouseover', (event) => {
            if (event.target.tagName === 'LI') {
                const description = event.target.getAttribute('data-description');
                if (description) {
                    popover.textContent = description;
                    popover.style.display = 'block';
                    popover.style.left = event.pageX + 'px';
                    popover.style.top = (event.pageY + 10) + 'px';
                }
            }
        });

        taskList.addEventListener('mousemove', (event) => {
            popover.style.left = event.pageX + 'px';
            popover.style.top = (event.pageY + 10) + 'px';
        });

        taskList.addEventListener('mouseout', (event) => {
            if (event.target.tagName === 'LI') {
                popover.style.display = 'none';
            }
        });

        function listTasks(page = 1) {
            fetch(`todo_api.php?page=${page}&limit=${limit}`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const { tasks, total, pages } = data;
                taskList.innerHTML = '';
                tasks.forEach(task => {
                    const li = document.createElement('li');
                    li.setAttribute('data-description', task.description);
                    li.innerHTML = `
                        ${task.title} 
                        <button class="completed" onclick="toggleTask(${task.id}, '${task.title}', '${task.description}', ${task.completed})">
                            ${task.completed ? 'Concluída' : 'Pendente'}
                        </button>
                        <button onclick="deleteTask(${task.id})">Excluir</button>
                    `;
                    taskList.appendChild(li);
                });

                currentPage = page;
                pageInfo.textContent = `Página ${currentPage} de ${pages}`;
                prevPage.disabled = currentPage === 1;
                nextPage.disabled = currentPage === pages;
            });
        }

        prevPage.addEventListener('click', () => {
            if (currentPage > 1) listTasks(currentPage - 1);
        });

        nextPage.addEventListener('click', () => {
            listTasks(currentPage + 1);
        });

        taskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const title = document.getElementById('taskTitle').value;
            const description = document.getElementById('taskDescription').value;

            fetch('todo_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: title, description: description })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                taskForm.reset();
                listTasks();
            });
        });

        function toggleTask(id, title, description, completed) {
            fetch('todo_api.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, title: title, description: description, completed: completed ? '0' : '1' })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                listTasks();
            });
        }

        function deleteTask(id) {
            fetch('todo_api.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                listTasks();
            });
        }

        listTasks();
    </script>
</body>
</html>
