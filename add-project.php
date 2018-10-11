<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}

$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

$projects_list = [];
$tasks_list_all = [];

require_once('functions.php');

if ($link) {
    $cur_user = $_SESSION['user']['id'];

    //Получаем список всех проектов этого пользователя
    $sql = 'SELECT * FROM projects WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {$projects_list = mysqli_fetch_all($result, MYSQLI_ASSOC);}

    //Получаем список всех задач этого пользователя
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {$tasks_list_all = mysqli_fetch_all($result, MYSQLI_ASSOC);}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project = $_POST;
    $errors = [];

    //Проверяем заполнено ли название задачи
    if (empty($project['name'])) {$errors['name'] = 'Укажите название проекта';}
    //И если заполнено, то не повторяет ли существующий проект
    elseif (in_array($project['name'], array_column($projects_list, 'name'))) {$errors['name'] = 'Такой проект уже существует';}

    //Если нашлись ошибки, показываем их, сохраняя введёные данные
    if (count($errors)) {$page_content = include_template('add-project.php', ['project' => $project, 'errors' => $errors]);}
    else {
        //Генерируем SQL-запрос
        $sql = 'INSERT INTO projects (name, user_id) VALUES (?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$project['name'], $cur_user]);
        $result = mysqli_stmt_execute($stmt);

        //Если задача без проблем добавилась, возвращаем пользователя на главную страницу, иначе показываем ошибку, сохраняя введённые данные.
        if ($result) {header("Location: /");}
        else {
            $errors['sql'] = mysqli_error($link);
            $page_content = include_template('add-project.php', ['project' => $project, 'errors' => $errors]);}
    }
}
else {$page_content = include_template('add-project.php',[]);}

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'title' => 'Добавление проекта — Дела в порядке'
    ]);

print($layout_content);