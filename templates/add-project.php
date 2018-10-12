<h2 class="content__main-heading">Добавление задачи</h2>
<? if (isset($errors['sql'])) {print ($errors['sql']);}?>
<form class="form"  action="" method="post">
	<div class="form__row">
		<label class="form__label" for="name">Название <sup>*</sup></label>
		<?php $classname = isset($errors['name']) ? "form__input--error" : "";
      $value = isset($form['name']) ? $form['name'] : ""; ?>
		<input class="form__input <?=$classname;?>" type="text" name="name" id="project_name" value="<?=$value;?>" placeholder="Введите название проекта">
		<?php if (isset($errors['name'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['name'].'</span></p>');}?>
	</div>

	<div class="form__row form__row--controls">
<?php if (!empty($errors)) {print ('<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>');}?>
		<input class="button" type="submit" name="" value="Добавить">
	</div>
</form>