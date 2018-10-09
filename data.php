<?php
$show_complete_tasks = 0;
if (isset($_GET['show_completed'])) {$show_complete_tasks = $_GET['show_completed'];}
$projects_list = [];
$tasks_list_all = [];
$tasks_list = [];
$user_info = [];

//Проверяем авторизацию
$authorized = 0;
//Выбираем пользователя по id
$cur_user = 1;

if ($link) {
    //Получаем информацию о пользователе
    $sql = 'SELECT * FROM users WHERE id = '.$cur_user;
    $result = mysqli_query($link, $sql);
    if ($result) {
        $user_info = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    //Получаем список всех проектов этого пользователя
    $sql = 'SELECT * FROM projects WHERE user_id = '.$cur_user;
    $result = mysqli_query($link, $sql);
    if ($result) {
        $projects_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Получаем список всех задач этого пользователя и сортируем по новизне
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$cur_user.' ORDER BY date_created DESC';
    $result = mysqli_query($link, $sql);
    if ($result) {
        $tasks_list_all = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $tasks_list =  $tasks_list_all;
    }

    //При наличии параметра project, получаем список всех задач для одного проекта и сортируем по новизне
    if (isset($_GET['project'])) {
        $cur_project = $_GET['project'];
        //Приводим параметр к целочисленному типу для защиты от SQL-инъекций
        settype($cur_project, 'integer');
        $project_ids = array_column($projects_list, 'id');

        $sql = 'SELECT * FROM tasks WHERE user_id = '.$cur_user.' AND project_id= '.$cur_project.' ORDER BY date_created DESC';
        $result = mysqli_query($link, $sql);
        if ($result) {
            $tasks_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        //Если параметр project пуст, либо если по этому id у пользователя не нашли ни одного проекта, то вместо содержимого страницы возвращаем код ответа 404
        if (!in_array($cur_project, $project_ids) || empty($cur_project)) {
            header("HTTP/1.1 404 Not Found");
            die();
        }
    }
}