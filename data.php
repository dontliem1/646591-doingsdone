<?php
$show_complete_tasks = rand(0, 1);
$projects_list = ["Входящие","Учеба","Работа","Домашние дела","Авто"];
$tasks_list = [
    [
        'title' => 'Собеседование в IT компании',
        'date' => '01.12.2018',
        'category' => $projects_list[2],
        'is_done' => false
    ],
    [
        'title' => 'Выполнить тестовое задание',
        'date' => '25.12.2018',
        'category' => $projects_list[2],
        'is_done' => false
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'date' => '21.12.2018',
        'category' => $projects_list[1],
        'is_done' => true
    ],
    [
        'title' => 'Встреча с другом',
        'date' => '22.12.2018',
        'category' => $projects_list[0],
        'is_done' => false
    ],
    [
        'title' => 'Купить корм для кота',
        'date' => '',
        'category' => $projects_list[3],
        'is_done' => false
    ],
    [
        'title' => 'Заказать пиццу',
        'date' => '',
        'category' => $projects_list[3],
        'is_done' => false
    ]
];