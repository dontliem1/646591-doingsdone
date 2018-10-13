<?php
require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (empty($errors) && $user) {
        if (!password_verify($form['password'], $user['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (empty($errors)) {
        $_SESSION['user'] = $user;
        header("Location: /");
        exit();
    }
}

$page_content = include_template('auth.php', [
    'errors' => $errors,
    'form' => $form
]);

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'title' => 'Регистрация — Дела в порядке',
]);

print($layout_content);