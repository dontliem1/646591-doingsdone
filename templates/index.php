<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed"
            <?php if ($show_complete_tasks): ?>checked<?php endif; ?>
            type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">

    <?php foreach ($tasks_list as $key => $task): ?>
        <tr class="tasks__item task <?php if($task['is_done'] === true) {print ("task--completed");}?> <?php if(check_important($task['date']) === true) {print ("task--important");}?>"
						<?php if($show_complete_tasks === 0 && $task['is_done'] === true) {print ("hidden");} ?>>
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" <?php if($task['is_done'] === true) {print ("checked");} ?>>
                    <span class="checkbox__text"><?=esc($task['title']);?></span>
                </label>
            </td>

            <td class="task__file">
                <a class="download-link" href="#"></a>
            </td>

            <td class="task__date"><?php if (empty($task['date']) === true) {print 'Нет';} else {print $task['date'];} ?></td>
        </tr>
    <?php endforeach; ?>

</table>