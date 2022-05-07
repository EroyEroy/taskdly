import {sendData, getData} from './data.js';
import {onTaskChange, onTaskDelete} from './popup.js';

document.addEventListener('DOMContentLoaded', () => {
    loadCurrentTasks();
});

function loadCurrentTasks() {
    const taskForm = document.querySelector('.tasks__form'),
          noTasksBanner = document.querySelector('.tasks__null');

    taskForm.innerHTML = '';
    noTasksBanner.classList.add('disabled');

    getData('/post-return-tasks')
    .then(response => {
        if (response.ok && response.tasks.length) {
            response.tasks.forEach(task => {
                appendTask(task.id, task.text, task.isActive);
            });
        } else {
            noTasksBanner.classList.remove('disabled');
        }
    });
}

function appendTask(taskId, taskText, taskIsActive) {
    const task = document.createElement('div');
    task.classList.add('tasks__item', 'active');
    task.innerHTML = `
        <div class="tasks__options">
            <button class="tasks__btn tasks__btn-complete"></button>
            <button class="tasks__btn tasks__btn-delete"></button>
        </div>
        <div class="tasks__result">
            <textarea class="tasks__textarea" data-id="${taskId}" data-active="${taskIsActive}" readonly>${taskText}</textarea>
            <div class="tasks__change">
                <button class="tasks__btn-change">Изменить</button>
            </div>
            <div class="tasks__change mobile">
                <button class="tasks__btn-change change-mobile">
                </button>
            </div>
        </div>
    `;

    const taskTextArea = task.querySelector('.tasks__textarea'),
          taskChangeButtons = task.querySelectorAll('.tasks__btn-change'),
          taskCompleteButton = task.querySelector('.tasks__btn-complete'),
          taskDeleteButton = task.querySelector('.tasks__btn-delete');

    taskChangeButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            onTaskChange(event, taskId);
        });
    });

    taskDeleteButton.addEventListener('click', (event) => {
        onTaskDelete(event, taskId);
    });

    taskCompleteButton.addEventListener('click', (event) => {
        event.preventDefault();

        const data = {
            taskId: taskId,
            taskIsActive: !task.classList.contains('complete'),
        };

        sendData('/post-change-task-status', JSON.stringify(data))
            .then(response => {
                if (response.ok) {
                    if (response.taskIsActive) {
                        task.classList.remove('complete');
                    } else {
                        task.classList.add('complete');
                    }
                }
            });
    });

    if (!taskIsActive) {
        task.classList.add('complete');
    }

    document.querySelector('.tasks__form').append(task);
}

export {appendTask, loadCurrentTasks};