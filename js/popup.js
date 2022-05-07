import {sendData} from './data.js';
import {appendTask, loadCurrentTasks} from './tasks.js';

// Выход из аккаунта.
const logOutPopup = document.querySelector('#logout-task'), 
      logOutButtons = document.querySelectorAll('.logout-btn'),
      logOutConfirm = document.querySelector('#logout-confirm'),
      logOutRefuse = document.querySelector('#logout-refuse');

logOutConfirm.addEventListener('click', (event) => {
    event.preventDefault();
    window.location.href = 'logout';
});

logOutRefuse.addEventListener('click', (event) => {
    event.preventDefault();
    logOutPopup.classList.toggle('active');
});


// Добавление новой записи.
const addTaskPopup = document.querySelector('#add-task'),
      addTaskButtons = document.querySelectorAll('.add-btn'),
      addTaskConfirm = document.querySelector('#add-confirm'),
      addTaskTextArea = document.querySelector('#add-textarea');

addTaskConfirm.addEventListener('click', (event) => {
    event.preventDefault();

    const notification = document.querySelector('#add-notification');

    const data = {
        taskText: addTaskTextArea.value,
    };

    sendData('/post-add-task', JSON.stringify(data))
        .then(response => {
            if (response.ok) {
                // Добавление записи, закрытие окна.
                addTaskPopup.classList.remove('active');

                addTaskTextArea.classList.remove('popup__textarea--error');
                addTaskTextArea.placeholder = 'Напишите здесь что-нибудь';

                appendTask(response.taskId, response.taskText, true);
                const noTasksBanner = document.querySelector('.tasks__null');
                noTasksBanner.classList.add('disabled');

                notification.classList.remove('active');
                notification.innerHTML = '';
            } 
            else if (Object.keys(response.errors).length) {
                // Генерация уведомлений.
                const error = response.errors.taskText;
                
                notification.classList.add('active');
                notification.innerHTML = error;
            }

            addTaskTextArea.value = '';
        });
});


// Изменение задачи.
const changeTaskPopup = document.querySelector('#change-task'),
      changeTaskConfirm = document.querySelector('#change-confirm'),
      changeTaskTextArea = document.querySelector('#change-textarea');

function onTaskChange(event, taskId) {
    event.preventDefault();

    localStorage.setItem('selectedTaskId', taskId);
    changeTaskPopup.classList.add('active');

    const selTaskTextArea = document.querySelector(`textarea[data-id="${taskId}"]`);
    changeTaskTextArea.value = selTaskTextArea.value;
}

changeTaskConfirm.addEventListener('click', (event) => {
    event.preventDefault();

    const notification = document.querySelector('#change-notification');

    const data = {
        taskId: localStorage.getItem('selectedTaskId'),
        taskText: changeTaskTextArea.value,
    };

    sendData('/post-change-task', JSON.stringify(data))
        .then(response => {
            if (response.ok) {
                changeTaskPopup.classList.remove('active');

                notification.classList.remove('active');
                notification.innerHTML = '';

                const selTaskTextArea = document.querySelector(`textarea[data-id="${data.taskId}"]`);
                selTaskTextArea.value = data.taskText;
            } else if (Object.keys(response.errors).length) {
                // Генерация уведомлений.
                const error = response.errors.taskText;

                notification.classList.add('active');
                notification.innerHTML = error;
            }

            changeTaskTextArea.value = '';
        });
});


// Удаление задачи.
const deleteTaskPopup = document.querySelector('#delete-task'),
      deleteTaskConfirm = document.querySelector('#delete-confirm'),
      deleteTaskRefuse = document.querySelector('#delete-refuse');

function onTaskDelete(event, taskId) {
    event.preventDefault();

    localStorage.setItem('selectedTaskId', taskId);
    deleteTaskPopup.classList.add('active');
}

deleteTaskConfirm.addEventListener('click', (event) => {
    event.preventDefault();

    const data = {
        taskId: localStorage.getItem('selectedTaskId'),
    };

    sendData('/post-delete-task', JSON.stringify(data))
        .then(response => {
            if (response.ok) {
                const selTaskItem = document.querySelector(`textarea[data-id="${data.taskId}"]`)
                    .closest('.tasks__item');
                selTaskItem.remove();

                if (document.querySelectorAll('.tasks__item').length === 0) {
                    const noTasksBanner = document.querySelector('.tasks__null');
                    noTasksBanner.classList.remove('disabled');
                }
            }

            deleteTaskPopup.classList.remove('active');
        });
});

deleteTaskRefuse.addEventListener('click', (event) => {
    event.preventDefault();

    deleteTaskPopup.classList.remove('active');
});


// Календарь.
new Datepicker('#calendar-select', {
    inline: true,
    yearRange: 1,
    weekStart: 1,

    min: (function(){
      let date = new Date();
      date.setDate(date.getDate() - 180);
      return date;
    })(),

    max: (function(){
      let date = new Date();
      date.setDate(date.getDate() + 180);
      return date;
    })(),

    i18n: {
        months: [
            'Январь', 'Февраль', 'Март', 'Апрель', 
            'Май', 'Июнь', 'Июль', 'Август', 
            'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        weekdays: [
            'Пн', 'Вт', 'Ср', 'Чт', 
            'Пт', 'Сб', 'Вс'
        ],
    },
});

const calendarPopup = document.querySelector('#calendar-task'),
      calendarButtons = document.querySelectorAll('.calendar-btn'),
      calendarSelect = document.querySelector('#calendar-select'),
      calendarConfirm = document.querySelector('#calendar-confirm');

calendarConfirm.addEventListener('click', (event) => {
    event.preventDefault();

    const selectedDate = calendarSelect.value;
    console.log(selectedDate);

    if (selectedDate) {
        const data = {
            selectedDate: selectedDate,
        };

        sendData('/post-change-date', JSON.stringify(data))
            .then(response => {
                if (response.ok) {
                    loadCurrentTasks();
                    calendarPopup.classList.remove('active');

                    document.querySelector('.tasks__data').innerHTML = selectedDate;
                }
            });
    }

    // calendarSelect.value = '';
});

window.addEventListener('unload', () => {
    calendarSelect.value = '';
});


// Открытие окон.
[
    [logOutButtons, logOutPopup],
    [addTaskButtons, addTaskPopup],
    [calendarButtons, calendarPopup]
].forEach(item => {
    const buttons = item[0],
          popup = item[1];

    // Одно событие для кнопок из нескольких вариантов меню.
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            popup.classList.add('active');
        });
    });
});


// Закрытие окон.
[logOutPopup, addTaskPopup, changeTaskPopup, deleteTaskPopup, calendarPopup].forEach(popup => {
    const closeButton = popup.querySelector('.popup__close');

    // Закрытие окна по нажатию на крестик.
    closeButton.addEventListener('click', () => {
        popup.classList.remove('active');
    });

    // Закрытие окна по клику вне области.
    popup.addEventListener('click', (event) => {
        if (event.target === popup) {
            popup.classList.remove('active');
        }
    });

    // Закрытие окна по нажатию клавиши Escape.
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            popup.classList.remove('active');
        }
    });
});

export {onTaskChange, onTaskDelete};