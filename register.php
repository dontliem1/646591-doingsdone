<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

require_once('functions.php');

$projects_list = [];
$tasks_list_all = [];
$form = [];
$errors = [];

session_start();

if ($link && isset($_SESSION['user'])) {
    $cur_user = $_SESSION['user']['id'];

    //Получаем список всех проектов этого пользователя
    $sql = 'SELECT * FROM projects WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {
        $projects_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Получаем список всех задач этого пользователя
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {
        $tasks_list_all = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    if (empty($form['name'])) {$errors['name'] = 'Введите своё имя';}

    if (empty($form['password'])) {$errors['password'] = 'Придумайте пароль';}

    if (empty($form['email']) || !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите действительный адрес электронной почты';
    }
    else {
    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {$errors['email'] = 'Пользователь с этим email уже зарегистрирован';}
    }

    if (empty($errors)) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (date, email, name, password) VALUES (NOW(), ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /auth.php");
            exit();
        } else {$errors['sql'] = mysqli_error($link);}
    }
}

$page_content = include_template('register.php', ['errors' => $errors, 'form' => $form]);

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'title' => 'Регистрация — Дела в порядке',
]);

print($layout_content);