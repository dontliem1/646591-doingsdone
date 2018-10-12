<?php
require_once 'functions.php';

$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

session_start();

$projects_list = [];
$tasks_list_all = [];

if ($link && isset($_SESSION['user'])) {
    //Получаем список всех проектов этого пользователя
    $sql = 'SELECT * FROM projects WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {$projects_list = mysqli_fetch_all($result, MYSQLI_ASSOC);}

    //Получаем список всех задач этого пользователя
    $sql = 'SELECT * FROM tasks WHERE user_id = '.$_SESSION['user']['id'];
    $result = mysqli_query($link, $sql);
    if ($result) {$tasks_list_all = mysqli_fetch_all($result, MYSQLI_ASSOC);}
}

$form = [];
$errors = [];