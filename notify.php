<?php
require_once 'vendor/autoload.php';
require_once 'init.php';

$tasks_undone = [];
$users_list = [];
$users_ids = [];

if ($link) {
    //Получаем список всех невыполненных задач
    $sql = 'SELECT * FROM tasks WHERE status = 0 AND deadline >= (NOW() - INTERVAL 1 HOUR)';
    $result = mysqli_query($link, $sql);
    if ($result) {
        $tasks_undone = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //Получаем список всех пользователей
    $sql = 'SELECT * FROM users';
    $result = mysqli_query($link, $sql);
    if ($result) {
        $users_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

if (!empty($tasks_undone)) {
    //Составляем массивы с уникальными пользователями с невыполненными задачами, и именами и адресами пользователей
    $user_ids = array_unique(array_column($tasks_undone, 'user_id'));
    $users = array_combine(array_column($users_list, 'id'),array_column($users_list, 'name'));
    $emails = array_combine(array_column($users_list, 'id'),array_column($users_list, 'email'));

    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");

    $mailer = new Swift_Mailer($transport);

    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    foreach ($user_ids as $key => $user) {
        $message = new Swift_Message();
        $message->setSubject("Уведомление от сервиса «Дела в порядке»");
        $message->setFrom(['keks@phpdemo.ru' => 'Дела в порядке']);
        $message->setBcc($emails[$user]);

        $tasks = [];
        $msg_content = 'Уважаемый '.$users[$user].'. ';

        foreach ($tasks_undone as $key => $task) {
          if ($task['user_id']===$user) {
              $tasks[] = '«'.$task["name"].'» на '.$task["deadline"];
          }
        }

        if (count($tasks) > 1) {
            $msg_content .= 'У вас запланированы следующие задачи: '.implode(', ', $tasks).'.';
        }
        else {
            $msg_content .= 'У вас запланирована задача '.$tasks[0];
        }

        $message->setBody($msg_content, 'text/plain');

        $result = $mailer->send($message);

        if ($result) {
            print('Письмо для пользователя с именем '.$users[$user].' успешно отправлено.<br>');
        }
        else {
            print('Не удалось отправить письмо для пользователя с именем '.$users[$user].': '.$logger->dump().'<br>');
        }
    }
}
else {
    print('Нет задач на отправку.');
}