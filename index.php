<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

$projects_list = [];
$tasks_list_all = [];
$tasks_list = [];

session_start();
if ($link && isset($_SESSION['user'])) {
    $cur_user = $_SESSION['user']['id'];

    //Получаем список всех проектов этого пользователя
    $sql = 'SELECT * FROM projects WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {
        $projects_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Получаем список всех задач этого пользователя и сортируем по новизне
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$_SESSION['user']['id'].' ORDER BY date_created DESC';
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

        $sql = 'SELECT * FROM tasks WHERE user_id = '.$_SESSION['user']['id'].' AND project_id= '.$cur_project.' ORDER BY date_created DESC';
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

require_once('functions.php');

if (isset($_SESSION['user'])) {$page_content = include_template('index.php',['tasks_list' => $tasks_list]);}
else {$page_content = include_template('guest.php',[]);}

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'title' => 'Дела в порядке'
    ]);

print($layout_content);