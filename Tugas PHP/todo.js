document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#new-task-form');
    const input = document.querySelector('#new-task-input');
    const tasksDiv = document.querySelector('#tasks');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (input.value.trim()) {
            const task = input.value.trim();

            // Send a POST request to add the new task
            fetch('your-php-file.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'add_task',
                    new-task-input: task
                })
            })
            .then(response => response.text())
            .then(data => {
                if (data.startsWith('Task added successfully')) {
                    // Create a new task element and append it to the tasksDiv
                    const taskEl = createTaskElement(task);
                    tasksDiv.appendChild(taskEl);

                    // Clear the input field
                    input.value = '';
                } else {
                    console.error(data);
                }
            });
        }
    });

    tasksDiv.addEventListener('click', (e) => {
        if (e.target.tagName === 'BUTTON') {
            const button = e.target;
            const taskEl = button.closest('.task');
            const taskId = taskEl.dataset.taskId;

            if (button.classList.contains('delete')) {
                // Send a POST request to delete the task
                fetch('your-php-file.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'delete_task',
                        task_id: taskId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    if (data.startsWith('Task deleted successfully')) {
                        taskEl.remove();
                    } else {
                        console.error(data);
                    }
                });
            } else if (button.classList.contains('done')) {
                // Send a POST request to mark the task as completed
                fetch('your-php-file.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'mark_completed',
                        task_id: taskId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    if (data.startsWith('Task marked as completed')) {
                        taskEl.classList.add('completed');
                        button.style.display = 'none';
                    } else {
                        console.error(data);
                    }
                });
            }
        }
    });

    function createTaskElement(task) {
        const taskEl = document.createElement('div');
        taskEl.classList.add('task');
        taskEl.dataset.taskId = generateUniqueId();

        const taskContentEl = document.createElement('div');
        taskContentEl.classList.add('content');

        const taskInputEl = document.createElement('input');
        taskInputEl.classList.add('text');
        taskInputEl.type = 'text';
        taskInputEl.value = task;
        taskInputEl.setAttribute('readonly', 'readonly');

        taskContentEl.appendChild(taskInputEl);
        taskEl.appendChild(taskContentEl);

        const taskActionsEl = document.createElement('div');
        taskActionsEl.classList.add('actions');

        const taskEditEl = document.createElement('button');
        taskEditEl.classList.add('edit');
        taskEditEl.innerText = 'Edit';

        const taskDeleteEl = document.createElement('button');
        taskDeleteEl.classList.add('delete');
        taskDeleteEl.innerText = 'Delete';

        const taskDoneEl = document.createElement('button');
        taskDoneEl.classList.add('done');
        taskDoneEl.innerText = 'Done';

        taskActionsEl.appendChild(taskEditEl);
        taskActionsEl.appendChild(taskDoneEl);
        taskActionsEl.appendChild(taskDeleteEl);

        taskEl.appendChild(taskActionsEl);

        return taskEl;
    }

    function generateUniqueId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
});