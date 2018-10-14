<h2 class="content__main-heading">Список задач</h2>
<form class="search-form" action="" method="GET">
    <input class="search-form__input" type="text" name="q" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="<?=make_link('date', false);?>" class="tasks-switch__item <?php if (!isset($_GET['date'])) {print ("tasks-switch__item--active");}?>">Все задачи</a>
        <a href="<?=make_link('date', 'today');?>" class="tasks-switch__item <?php if (isset($_GET['date']) && $_GET['date'] === 'today') {print ("tasks-switch__item--active");}?>">Повестка дня</a>
        <a href="<?=make_link('date', 'tomorrow');?>" class="tasks-switch__item <?php if (isset($_GET['date']) && $_GET['date'] === 'tomorrow') {print ("tasks-switch__item--active");}?>">Завтра</a>
        <a href="<?=make_link('date', 'past');?>" class="tasks-switch__item <?php if (isset($_GET['date']) && $_GET['date'] === 'past') {print ("tasks-switch__item--active");}?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed"
            <?php if (isset($_GET['show_completed']) && $_GET['show_completed']) {print ("checked");}?>
            type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>
<?php if (!empty($tasks_list)): ?>
<table class="tasks">

    <?php foreach ($tasks_list as $key => $task): ?>
        <tr class="tasks__item task <?php if($task['status']) {print ("task--completed");}?> <?php if(check_important($task['deadline']) && !$task['status']) {print ("task--important");}?>"
						<?php if((!isset($_GET['show_completed']) || !$_GET['show_completed']) && $task['status'] || filter_date($task['deadline'], $task['date_done'])) {print ("hidden");} ?>>
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$task['id'];?>" <?php if($task['status']) {print ("checked");} ?>>
                    <span class="checkbox__text"><?=esc($task['name']);?></span>
                </label>
            </td>
					<td class="task__file">
							<?php if($task['file_path']) {print ('<a class="download-link" href="'.esc($task['file_path']).'"></a>');}?>
					</td>
            <td class="task__date"><?php if (empty($task['deadline'])) {print 'Нет';} else {print date('d.m.Y', strtotime($task['deadline']));} ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php elseif (isset($_GET['q'])): ?>
<p>Ничего не найдено по вашему запросу.</p>
<?php endif; ?>