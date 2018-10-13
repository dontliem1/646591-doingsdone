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
        $errors['name'] = 'Укажите название задачи';
    }

    //Проверяем выбран ли проект и существует ли он
    if (empty($form['project'])) {
        $errors['project'] = 'Укажите проект';
    }
    elseif (!in_array($form['project'], array_column($projects_list, 'id'))) {
        $errors['project'] = 'Выберите существующий проект';
    }

    //Проверяем формат даты при наличии
    if (empty($form['date'])) {
        $form['date'] = null;
    }
    elseif ($form['date'] !== date('Y-m-d', strtotime($date))) {
        $errors['date'] = 'Введите дату в формате дд.мм.гггг';
    }

    //Проверяем тип загруженного файла при наличии
    if (!empty($_FILES['preview']['name'])) {
        $tmp_name = $_FILES['preview']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        $types = array("image/jpeg", "image/png", "text/plain");
        if (!in_array($file_type, $types)) {
            $errors['file'] = 'Загрузите картинку или текстовый файл';
        }
    }

    if (empty($errors)) {
        //Если пользователь загрузил файл, перемещаем его в корень сайта
        if (isset($tmp_name)) {
            $path = $_FILES['preview']['name'];
            move_uploaded_file($tmp_name, '/'.$path);
            $form['path'] = $path;
        }
        else {
            $form['path'] = '';
        }

        //Генерируем SQL-запрос
        $sql = 'INSERT INTO tasks (date_created, status, name, file_path, deadline, user_id, project_id) VALUES (NOW(), 0, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['name'], $form['path'], $form['date'], $_SESSION['user']['id'], $form['project']]);

        $result = mysqli_stmt_execute($stmt);

        //Если задача без проблем добавилась, возвращаем пользователя на главную страницу, иначе показываем ошибку, сохраняя введённые данные.
        if ($result) {
            header("Location: /");
        }
        else {
            $errors['sql'] = mysqli_error($link);
        }
    }
}

$page_content = include_template('add-task.php', [
    'projects_list' => $projects_list,
    'errors' => $errors,
    'form' => $form
]);

$layout_content = include_template('layout.php',[
    'content' => $page_content,
    'projects_list' => $projects_list,
    'tasks_list_all' => $tasks_list_all,
    'title' => 'Добавление задачи — Дела в порядке'
    ]);

print($layout_content);