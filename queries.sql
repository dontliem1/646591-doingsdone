USE doingsdone;

-- Добавляем список проектов
INSERT INTO projects
VALUES  (1, 'Входящие', 1),
        (2, 'Учеба', 1),
        (3, 'Работа', 2),
        (4, 'Домашние дела', 1),
        (5, 'Авто', 2);

-- Добавляем пару пользователей
INSERT INTO users
VALUES  (1, '2018-10-01 10:00:00', 'dontliem1@gmail.com', 'Михаил', 'pass', '+7 922 007-68-33'),
        (2, '2018-10-02 08:00:00', 'dontliem1@yandex.ru', 'Константин', 'pass2', '+7 999 999-99-99');

-- Добавляем задачи
INSERT INTO tasks
VALUES  (1, '2018-10-01 10:10:00', NULL, 0, 'Собеседование в IT компании', NULL, '2018-12-01 00:00:00', 2, 3),
        (2, '2018-10-01 10:11:00', NULL, 0, 'Выполнить тестовое задание', NULL, '2018-12-25 00:00:00', 2, 3),
        (3, '2018-10-01 10:12:00', '2018-10-02 04:00:00', 1, 'Сделать задание первого раздела', NULL, '2018-12-21 00:00:00', 1, 2),
        (4, '2018-10-01 10:13:00', NULL, 0, 'Встреча с другом', NULL, '2018-12-22 00:00:00', 1, 1),
        (5, '2018-10-01 10:14:00', NULL, 0, 'Купить корм для кота', NULL, NULL, 1, 4),
        (6, '2018-10-01 10:15:00', NULL, 0, 'Заказать пиццу', NULL, NULL, 1, 4);

-- Получаем список всех проектов одного пользователя
SELECT * FROM projects WHERE user_id = 1;

-- Получаем список всех задач в одном проекте
SELECT * FROM tasks WHERE project_id = 3;

-- Помечаем задачу как выполненную
UPDATE tasks
SET date_done = CURRENT_TIMESTAMP,
    status = 1
WHERE id = 1;

-- Получаем все задачи для завтрашнего дня
SELECT * FROM tasks WHERE DATEDIFF(deadline, CURDATE()) = 1;

-- Обновляем название задачи по её id
UPDATE tasks SET name = 'Написать SQL-запросы' WHERE id = 1;