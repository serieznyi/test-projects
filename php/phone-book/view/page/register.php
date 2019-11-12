<?php

/**
 * @var View $context
 * @var RegistrationForm $form
 */

$context->vars['title'] = 'Registration';

use App\Form\RegistrationForm;
use App\View;
use App\Web\Util\ValidationUtil;

?>

<form action="/register" method="POST">
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" required="required" name="login" id="login" value="<?=$form->login?>" aria-describedby="emailHelp" placeholder="Enter login">
        <small id="emailHelp" class="form-text text-muted">Only letters in lowercase and numbers</small>
        <?php ValidationUtil::renderFieldErrors($form, 'login'); ?>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" required="required" name="email" id="login" value="<?=$form->email?>" aria-describedby="emailHelp" placeholder="Enter email">
        <?php ValidationUtil::renderFieldErrors($form, 'email'); ?>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" required="required" name="password" id="password" value="<?=$form->password?>" placeholder="Password">
        <?php ValidationUtil::renderFieldErrors($form, 'password'); ?>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm password</label>
        <input type="password" class="form-control" required="required" name="confirm_password" id="confirm_password" value="<?=$form->passwordConfirm?>" placeholder="Repeat password">
        <?php ValidationUtil::renderFieldErrors($form, 'passwordConfirm'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <a class="btn btn-default" href="/login">Login</a>
</form>