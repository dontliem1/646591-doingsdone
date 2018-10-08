<h2 class="content__main-heading">Добавление задачи</h2>
<? if (isset($errors['sql'])) {print ($errors['sql']);}?>
<form class="form"  action="" method="post" enctype="multipart/form-data">
	<div class="form__row">
		<label class="form__label" for="name">Название <sup>*</sup></label>
		<?php $classname = isset($errors['name']) ? "form__input--error" : "";
      $value = isset($task['name']) ? $task['name'] : ""; ?>
		<input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=$value;?>" placeholder="Введите название">
		<?php if (isset($errors['name'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['name'].'</span></p>');}?>
	</div>

	<div class="form__row">
		<label class="form__label" for="project">Проект <sup>*</sup></label>
		<?php $classname = isset($errors['project']) ? "form__input--error" : "";?>
		<select class="form__input form__input--select <?=$classname;?>" name="project" id="project">
      <?php foreach ($projects_list as $project): ?>
				<option value="<?=$project['id'];?>"><?=esc($project['name']);?></option>
      <?php endforeach; ?>
		</select>
		<?php if (isset($errors['project'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['project'].'</span></p>');}?>
	</div>

	<div class="form__row">
		<label class="form__label" for="date">Дата выполнения</label>
      <?php $classname = isset($errors['date']) ? "form__input--error" : "";
      $value = isset($task['date']) ? $task['date'] : ""; ?>
		<input class="form__input form__input--date <?=$classname;?>" type="date" name="date" id="date" value="<?=$value;?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ" maxlength="10">
		<?php if (isset($errors['date'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['date'].'</span></p>');}?>
	</div>

	<div class="form__row">
		<label class="form__label" for="preview">Файл</label>
		<div class="form__input-file">
        <?php $classname = isset($errors['file']) ? "form__input--error" : "";?>
			<input class="visually-hidden" type="file" name="preview" id="preview" value="">
			<label class="button button--transparent <?=$classname;?>" for="preview">
				<span>Выберите файл</span>
			</label>
		</div>
      <?php if (isset($errors['file'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['file'].'</span></p>');}?>
	</div>
	<div class="form__row form__row--controls">
<?php if (!empty($errors)) {print ('<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>');}?>
		<input class="button" type="submit" name="" value="Добавить">
	</div>
</form>