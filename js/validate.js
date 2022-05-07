// Валидация данных формы страниц регистрации и авторизации.

import {sendData} from './data.js';

const form = document.querySelector('.preparation__form'),
    notifications = document.querySelectorAll('.notification');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Деактивация всех видимых уведомлений.
    notifications.forEach(notification => {
        notification.innerHTML = '';
        notification.classList.remove('active');
    });

    // Данные формы.
    const formData = new FormData(form);
    const url = '/post-' + window.location.pathname.slice(1);

    // Данные для отправки на сервер.
    const data = {};
    for (let [name, value] of formData) {
        data[name] = value;
    }

    sendData(url, JSON.stringify(data))
        .then(response => {
            if (response.ok) {
                // Успех авторизации, редирект на главную страницу.
                window.location.href = 'home';
            }
            else if (Object.keys(response.errors).length) {
                // Генерация уведомлений.
                for (let name in response.errors) {
                    const text = response.errors[name], 
                        input = document.querySelector(`input[name="${name}"]`);

                    if (input) {
                        input.value = '';

                        const notification = input.nextElementSibling;
                        notification.innerHTML = text;
                        notification.classList.add('active');
                    }
                }
            }
        });
});