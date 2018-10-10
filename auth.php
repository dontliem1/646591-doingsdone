<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

session_start();
require_once('functions.php');

$projects_list = [];
$form = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (!password_verify($form['password'], $user['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('auth.php', ['form' => $form, 'errors' => $errors]);
    }
    else {
        $_SESSION['user'] = $user;
        header("Location: /");
        exit();
    }
}
else {
    if (isset($_SESSION['user'])) {header("Location: /");}
    else {$page_content = include_template('auth.php', []);}
}

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'title' => 'Регистрация — Дела в порядке',
]);

print($layout_content);