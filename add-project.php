<?php
require_once('init.php');

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    //Проверяем заполнено ли название задачи
    if (empty($form['name'])) {
        $errors['name'] = 'Укажите название проекта';
    }
    //И если заполнено, то не повторяет ли существующий проект
    elseif (in_array($form['name'], array_column($projects_list, 'name'))) {
        $errors['name'] = 'Такой проект уже существует';
    }

    //Если нашлись ошибки, показываем их, сохраняя введёные данные
    if (empty($errors)) {
        //Генерируем SQL-запрос
        $sql = 'INSERT INTO projects (name, user_id) VALUES (?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['name'], $_SESSION['user']['id']]);
        $result = mysqli_stmt_execute($stmt);

        //Если задача без проблем добавилась, возвращаем пользователя на главную страницу, иначе показываем ошибку
        if ($result) {
            header("Location: /");
            exit();
        }
        else {
            $errors['sql'] = mysqli_error($link);
        }
    }
}

$page_content = include_template('add-project.php', [
    'form' => $form,
    'errors' => $errors
]);

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'title' => 'Добавление проекта — Дела в порядке'
    ]);

print($layout_content);