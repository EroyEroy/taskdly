<?php
    // Подключение к базе-данных.
    $connect = mysqli_connect('localhost', 'mysql', '', 'taskdly');

    // Завершение работы при ошибке с подключением.
    if (!$connect) {
        die('DATABASE: CONNECTION ERROR!');
    }