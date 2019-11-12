<?php

/**
 * @var App\View $context
 * @var \App\Form\LoginForm $form
 */

$context->vars['title'] = 'Login';

use App\Web\Util\ValidationUtil;

?>

<form action="/login" method="POST">
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" required="required" name="login" id="login" value="<?=$form->login?>" aria-describedby="emailHelp" placeholder="Enter login">
        <?php ValidationUtil::renderFieldErrors($form, 'login'); ?>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" required="required" name="password" id="password" value="<?=$form->password?>" placeholder="Password">
        <?php ValidationUtil::renderFieldErrors($form, 'password'); ?>
    </div>
    <button type="submit" class="btn btn-success">Login</button>
    <a class="btn btn-default" href="/register">Register</a>
</form>