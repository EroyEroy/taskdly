<?php

    session_start();

    // Создание массива уведомлений при его отстутствии в сессии.
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $routes = [];


    // Маршруты:
    route('/login', function () {
        $user_logged = isset($_SESSION['user']);
        if ($user_logged) {
            header('Location: home');
        } else {
            include 'pages/login.php';
        }
    });

    route('/register', function () {
        $user_logged = isset($_SESSION['user']);
        if ($user_logged) {
            header('Location: home');
        } else {
            include 'pages/register.php';
        }
    });

    route('/logout', function () {
        $user_logged = isset($_SESSION['user']);

        // Удаление пользователя из сессии.
        if ($user_logged) {
            unset($_SESSION['user']);
        } 

        header('Location: login');
    });

    route('/404', function () {
        include 'pages/404.php';
    });

    route('/home', function () {
        $user_logged = isset($_SESSION['user']);
        if (!$user_logged) {
            header('Location: login');
        } else {
            include 'pages/home.php';
        }
    });

    // Обработка POST запросов:
    route('/post-register', function () {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = [
            'ok' => false,
            'errors' => [],
        ];

        // Проверка пользователя, входных данных.
        if (!$data) {
            $response['errors']['data'] = 'Данные не были получены';
        } elseif ($user_logged) {
            $response['errors']['user'] = 'Пользователь авторизирован';
        } else {
            // Обработка данных.
            require_once 'connect.php';

            if ($connect) {
                // Успешное подключение к базе данных.

                $email = $data['email'] ?? null;
                $password = $data['password'] ?? null;
                $password_confirm = $data['passwordConfirm'] ?? null;
    
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Валидация почты.
                    $response['errors']['email'] = 'Ваш e-mail адрес указан неверно';
                } else {
                    // Проверка на существование аккаунта с указанной почтой.
                    $query = "SELECT * FROM `users` WHERE `email`='$email'";
                    $result = mysqli_query($connect, $query);
                    if (mysqli_num_rows($result)) {
                        $response['errors']['email'] = 'Указанный e-mail адрес уже занят';
                    }
                }
    
                if (mb_strlen($password) <= 5 || mb_strlen($password) > 15) {
                    // Проверка на допустимую длинную пароля.
                    $response['errors']['password'] = 'Пароль должен быть длинной от 6 до 15 символов';
                } elseif (!preg_match("#[0-9a-zA-Z]+#", $password)) {
                    // Проверка на допустимые символы в пароле.
                    $response['errors']['password'] = 'Пароль должен содержать цифры и латинские буквы';
                }
    
                if ($password !== $password_confirm) {
                    // Проверка на совпадение паролей из двух полей.
                    $response['errors']['passwordConfirm'] = 'Пароли не совпадают';
                }
            
                if (empty($response['errors'])) {
                    // Создание аккаунта при отсутствии ошибок валидации.
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('$email', '$password_hash')";
                    $result = mysqli_query($connect, $query);
    
                    if ($result) {
                        // Сохранение пользователя в сессию.
                        $_SESSION['user'] = [
                            'id' => mysqli_insert_id($connect),
                            'timestamp' => strtotime('today 00:00 UTC'),
                        ];
    
                        $response['ok'] = true;
                    } else {
                        $response['errors']['email'] = 'Ошибка создания аккаунта';
                    }
                }
            } else {
                // Неуспешное подключение к базе данных.
                $response['errors']['database'] = 'Ошибка подключения к базе данных';
            }
        }

        echo json_encode($response);
    });

    route('/post-login', function() {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = [
            'ok' => false,
            'errors' => [],
        ];

        // Проверка пользователя, входных данных.
        if (!$data) {
            $response['errors']['data'] = 'Данные не были получены';
        } elseif ($user_logged) {
            $response['errors']['user'] = 'Пользователь авторизирован';
        } else {
            // Обработка данных.
            require_once 'connect.php';

            if ($connect) {
                // Успешное подключение к базе данных.
                $email = $data['email'] ?? null;
                $password = $data['password'] ?? null;
    
                $query = "SELECT * FROM `users` WHERE `email` = '$email'";
                $result = mysqli_query($connect, $query);
    
                $user = mysqli_fetch_assoc($result);
    
                if ($user && password_verify($password, $user['password'])) {
                    // Проверка правильности ввода пароля пользователя.
                    $response['ok'] = true;
    
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'timestamp' => strtotime('today 00:00 UTC'),
                    ];
                } else {
                    // Пользователь не найден.
                    $response['errors']['password'] = 'Неверный e-mail адрес или пароль';
                }
            } else {
                // Неуспешное подключение к базе данных.
                $response['errors']['database'] = 'Ошибка подключения к базе данных';
            }
        }

        echo json_encode($response);
    });

    route('/post-add-task', function () {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = [
            'ok' => false,
            'errors' => []
        ];

        if ($data && $user_logged) {
            require_once 'connect.php';

            $task_text = $data['taskText'];

            if (mb_strlen($task_text) > 10 && mb_strlen($task_text) < 150) {
                $user_id = $_SESSION['user']['id'];
                $timestamp = date('Y-m-d H:i:s', $_SESSION['user']['timestamp']);
    
                $query = "INSERT INTO `tasks` (`user_id`, `timestamp`, `text`)
                    VALUES ('$user_id', '$timestamp', '$task_text')
                ";
                $result = mysqli_query($connect, $query);
    
                if ($result) {
                    $response = [
                        'ok' => true,
                        'taskId' => mysqli_insert_id($connect),
                        'taskText' => $task_text,
                        'timestamp' => $timestamp,
                    ];
                } else {
                    $response['errors']['database'] = 'Ошибка добавления записи в базу данных';
                }
            } else {
                // Текст не имеет разрешенной длины.
                $response['errors']['taskText'] = 'Длина вашей записи должна быть от 10 до 150 символов';
            }
        }

        echo json_encode($response);
    });

    route('/post-delete-task', function () {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = ['ok' => false];
        
        if ($data && $user_logged) {
            require_once 'connect.php';

            $task_id = $data['taskId'];
            $user_id = $_SESSION['user']['id'];

            $query = "SELECT * FROM `tasks` WHERE
                `user_id` = '$user_id' AND `id` = '$task_id'
            ";
            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) === 1) {
                $query = "DELETE FROM `tasks` WHERE
                    `id` = '$task_id'
                ";
                $result = mysqli_query($connect, $query);
                if ($result) {
                    $response['ok'] = true;
                }
            }
        }

        echo json_encode($response);
    });

    route('/post-change-task', function () {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = [
            'ok' => false,
            'errors' => [],
        ];

        if ($data && $user_logged) {
            require_once 'connect.php';

            $task_text = $data['taskText'];

            if (mb_strlen($task_text) > 10 && mb_strlen($task_text) < 150) {
                $task_id = $data['taskId'];
                $user_id = $_SESSION['user']['id'];
    
                $query = "UPDATE `tasks` SET `text` = '$task_text'
                    WHERE `id` = '$task_id' AND `user_id` = '$user_id'
                ";
                mysqli_query($connect, $query);
                
                $changed = (boolean) mysqli_fetch_assoc(
                    mysqli_query($connect, "SELECT ROW_COUNT()")
                )['ROW_COUNT()'];

                if ($changed) {
                    $response['ok'] = true;
                } else {
                    $response['errors']['taskText'] = 'Текст записи остался прежним';
                }
            } else {
                // Текст не имеет разрешенной длины.
                $response['errors']['taskText'] = 'Длина вашей записи должна быть от 10 до 150 символов';
            }
        }

        echo json_encode($response);
    });

    route('/post-change-task-status', function () {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = ['ok' => false];

        if ($data && $user_logged) {
            require_once 'connect.php';

            $task_id = (int) $data['taskId'];
            $user_id = (int) $_SESSION['user']['id'];
            $task_new_status = (int) (! (boolean) $data['taskIsActive']);

            $query = "UPDATE `tasks` SET `is_active` = $task_new_status
                WHERE `id` = '$task_id' AND `user_id` = '$user_id'
            ";
            mysqli_query($connect, $query);

            $changed = (int) mysqli_fetch_assoc(
                mysqli_query($connect, "SELECT ROW_COUNT()")
            )['ROW_COUNT()'];

            if ($changed) {
                $response = [
                    'ok' => true,
                    'taskIsActive' => $task_new_status,
                ];
            }
        }

        echo json_encode($response);
    });

    route('/post-change-date', function() {
        $user_logged = isset($_SESSION['user']);
        $data = receive_json();
        $response = ['ok' => false];

        if ($data && $user_logged) {
            require_once 'connect.php';

            $selected_date = $data['selectedDate'];
            $new_timestamp = strtotime($selected_date);

            $date_is_correct = check_datetime($selected_date);

            if ($date_is_correct && ($new_timestamp !== $_SESSION['user']['timestamp'])) {
                $_SESSION['user']['timestamp'] = $new_timestamp + (60 * 60 * 3);
                $response['ok'] = true;
            }
        }

        echo json_encode($response);
    });

    route('/post-return-tasks', function() {
        $user_logged = isset($_SESSION['user']);
        $response = [
            'ok' => false,
            'tasks' => []
        ];

        if ($user_logged) {
            require_once 'connect.php';

            $user_id = $_SESSION['user']['id'];
            $timestamp = date('Y-m-d H:i:s', $_SESSION['user']['timestamp']);

            $query = "SELECT `text`, `id`, `is_active`  FROM `tasks` WHERE
                `user_id` = '$user_id' AND `timestamp` = '$timestamp'
            ";
            $result = mysqli_query($connect, $query);
            
            if ($result) {
                for ($tasks = []; $task = mysqli_fetch_assoc($result);) {
                    $tasks[] = [
                        'id' => (int) $task['id'],
                        'text' => (string) $task['text'],
                        'isActive' => (int) $task['is_active'],
                    ];
                }

                $response = [
                    'ok' => true,
                    'tasks' => $tasks,
                    'date' => date('d.m.Y H:i:s', $_SESSION['user']['timestamp'])
                ];
            }
        }

        echo json_encode($response);
    });

    run();

    // Обработка поступающего JSON данных:
    function receive_json() {
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
        if ($content_type === 'application/json') {
            $content = trim(file_get_contents('php://input'));
            $decoded = json_decode($content, true);

            return is_array($decoded) ? $decoded : null;
        }

        return null;
    }

    // Функции маршрутизации:
    function route($path, $callback) {
        global $routes;
        $routes[$path] = $callback;
    }

    function run() { 
        global $routes;
        $uri = $_SERVER['REQUEST_URI'];
        $is_found = false;

        foreach ($routes as $path => $callback) {
            if ($uri !== $path) continue;

            $is_found = true;
            $callback();
            break;
        }

        if (!$is_found) {
            $not_found_callback = $routes['/404'];
            $not_found_callback();
        }
    }

    function check_datetime($x) {
        return (date('d.m.Y', strtotime($x)) == $x);
    }