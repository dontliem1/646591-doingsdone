<?php
$show_complete_tasks = rand(0, 1);

$projects_list = [];
$tasks_list = [];
$user_info = [];

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

    //Получаем список всех задач этого пользователя
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$cur_user;
    $result = mysqli_query($link, $sql);
    if ($result) {
        $tasks_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}