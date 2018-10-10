<h2 class="content__main-heading">Регистрация аккаунта</h2>
<? if (isset($errors['sql'])) {print ($errors['sql']);}?>
<form class="form"  action="" method="post">
	<div class="form__row">
		<label class="form__label" for="email">E-mail <sup>*</sup></label>
		<?php $classname = isset($errors['email']) ? "form__input--error" : "";
      $value = isset($form['email']) ? $form['email'] : ""; ?>
		<input class="form__input <?=$classname;?>" type="text" name="email" id="email" value="<?=$value;?>" placeholder="Введите e-mail">
		<?php if (isset($errors['email'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['email'].'</span></p>');}?>
	</div>

	<div class="form__row">
		<label class="form__label" for="password">Пароль <sup>*</sup></label>
      <?php $classname = isset($errors['password']) ? "form__input--error" : "";
      $value = isset($form['password']) ? $form['password'] : ""; ?>
		<input class="form__input <?=$classname;?>" type="password" name="password" id="password" value="<?=$value;?>" placeholder="Введите пароль">
      <?php if (isset($errors['password'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['password'].'</span></p>');}?>
	</div>

	<div class="form__row">
		<label class="form__label" for="name">Имя <sup>*</sup></label>
      <?php $classname = isset($errors['name']) ? "form__input--error" : "";
      $value = isset($form['name']) ? $form['name'] : ""; ?>
		<input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=$value;?>" placeholder="Введите имя">
		<?php if (isset($errors['name'])) {print ('<p class="form_message"><span class="form__message error-message">'.$errors['name'].'</span></p>');}?>
	</div>

	<div class="form__row form__row--controls">
<?php if (!empty($errors)) {print ('<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>');}?>
		<input class="button" type="submit" name="" value="Зарегистрироваться">
	</div>
</form>