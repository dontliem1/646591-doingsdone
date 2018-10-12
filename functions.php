<?php
//Функция для подсчёта задач в проекте
function count_tasks($list, $project) {
    $categories = array_column($list, 'project_id');
    $count = array_count_values($categories);
    if (array_key_exists($project, $count)) {
        return $count[$project];
    }
    return '0';
}
//Функция для фильтрации данных
function esc($str) {
    $text = htmlspecialchars($str);
    return $text;
}
//Функция для включения шаблона в вёрстку с передачей параметров
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require($name);

    $result = ob_get_clean();

    return $result;
}
//Функция для генерации ссылок с параметрами
function make_link($parameter, $value) {
    $url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_parts = parse_url($url);
    $result = 'http://'.$_SERVER['HTTP_HOST'].'/';

    if (isset($url_parts['query'])) {
        parse_str($url_parts['query'], $params);
        if ($value) {
            $params[$parameter] = $value;
        }
        else {
            unset($params[$parameter]);
        }
        $url_parts['query'] = http_build_query($params);
        if (!empty($url_parts['query'])) {
            $result = '?' . $url_parts['query'];
        }
    }
    elseif ($value) {
        $result = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?'.$parameter.'=' . $value;
    }

    return $result;
}
//Функция для проверки срока истечения задачи
function check_important($date) {
    $important = false;
    $difference = floor((strtotime($date) - time())/3600);
    if (!empty($date) && ($difference <= 24)) {
        $important = true;
    }
    return $important;
}
//Функция для фильтрации задач
function filter_date($date, $done) {
    $result = false;
    //При наличии параметра date, фильтруем задачи соответствующим образом
    if (isset($_GET['date'])) {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', mktime(0,0, 0, date("m"), date("d")+1, date("Y")));
        $yesterday = date('Y-m-d', mktime(0,0, 0, date("m"), date("d")-1, date("Y")));
        if (//Прячем все задачи с дедлайнами, отличными от сегодня
            ($_GET['date'] === 'today' && $date !== $today) ||
            //Прячем все задачи, кроме завтрашних
            ($_GET['date'] === 'tomorrow' && $date !== $tomorrow) ||
            //Прячем задачи с пустым или ненаступившим дедлайном, или задачи, выполненные в срок
            ($_GET['date'] === 'past' && (empty($date) || $date > $yesterday || (!empty($done) && strtotime($done) < (strtotime($date) + 86400))))
        ) {
            $result = true;
        }
    }
    return $result;
}
//Функция для проверки формата даты
function validate_date($date, $format= 'Y-m-d'){
    return $date === date($format, strtotime($date));
}
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value) || is_null($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}