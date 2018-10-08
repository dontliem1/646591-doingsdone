<?php
$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($link, "utf8");

require_once('functions.php');
require_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST;
    $errors = [];

    //Проверяем заполнено ли название задачи
    if (empty($task['name'])) {
        $errors['name'] = 'Укажите название задачи';
    }

    //Проверяем выбран ли проект и существует ли он
    if (empty($task['project'])) {
        $errors['project'] = 'Укажите проект';
    }
    elseif (!in_array($task['project'], array_column($projects_list, 'id'))) {
        $errors['project'] = 'Выберите существующий проект';
    }

    //Проверяем формат даты
    if (!empty($task['date']) && !validate_date($task['date'])) {
        $errors['date'] = 'Введите дату в формате дд.мм.гггг';
    }

    if (isset($_FILES['preview']['name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $path = $_FILES['preview']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        $types = array("image/jpeg", "image/png", "text/plain");
        if (!in_array($file_type, $types)) {
            $errors['file'] = 'Загрузите картинку или текстовый файл';
        }
        else {
            move_uploaded_file($tmp_name, '/'.$path);
            $task['path'] = $path;
        }
    }

    //Если проверки не сработали, показываем ошибки, сохраняя введёные данные
    if (count($errors)) {
        $page_content = include_template('add-task.php', ['projects_list' => $projects_list, 'task' => $task, 'errors' => $errors]);
    }
    else {
        //Если пользователь загрузил файл, перемещаем его в корень сайта
        if (isset($_FILES['preview']['name'])) {
            $tmp_name = $_FILES['preview']['tmp_name'];
            $path = $_FILES['preview']['name'];

            move_uploaded_file($tmp_name, '/'.$path);
            $task['path'] = $path;
        }

        //Генерируем SQL-запрос в зависимости от наличия заполненной даты
        if (empty($task['date'])) {
            $sql = 'INSERT INTO tasks (date_created, status, name, file_path, user_id, project_id) VALUES (NOW(), 0, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$task['name'], $task['path'], $cur_user, $task['project']]);
        }
        else {
            $sql = 'INSERT INTO tasks (date_created, status, name, file_path, deadline, user_id, project_id) VALUES (NOW(), 0, ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$task['name'], $task['path'], $task['date'], $cur_user, $task['project']]);
        }
        $result = mysqli_stmt_execute($stmt);

        //Если задача без проблем добавилась, возвращаем пользователя на главную страницу, иначе показываем ошибку, сохраняя введённые данные.
        if ($result) {header("Location: /");}
        else {
            $errors['sql'] = mysqli_error($link);
            $page_content = include_template('add-task.php', ['projects_list' => $projects_list, 'task' => $task, 'errors' => $errors]);}
    }
}
else {
    $page_content = include_template('add-task.php',[
        'projects_list' => $projects_list
    ]);
}

$layout_content = include_template('layout.php',[
		'content' => $page_content,
		'projects_list' => $projects_list,
		'tasks_list_all' => $tasks_list_all,
		'tasks_list' => $tasks_list,
		'user' => esc($user_info['name']),
		'title' => 'Добавление задачи — Дела в порядке'
		]);

print($layout_content);