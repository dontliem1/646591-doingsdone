<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

require_once('functions.php');
require_once('data.php');

$form = [];
$errors = [];

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
            header("Location: /");
            exit();
        } else {$errors['sql'] = mysqli_error($link);}
    }
}

$page_content = include_template('register.php', ['errors' => $errors, 'form' => $form]);

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'authorized' => $authorized,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'tasks_list' => $tasks_list,
    'user' => esc($user_info['name']),
    'title' => 'Регистрация — Дела в порядке',
]);

print($layout_content);