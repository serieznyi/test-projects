<?php

/**
 * @var \App\View $context
 * @var \App\Form\BookForm $form
 * @var int $id
 * @var string $imagesRoot
 */

use App\Web\Util\ValidationUtil;

$context->vars['title'] = "Update phone $id";

?>

<form action="/phone/update/<?=$id?>" method="post">
    <input type="hidden" value="<?=$id?>">
    <div class="form-group">
        <label for="firstName">First name</label>
        <input type="text" name="firstName" class="form-control" id="firstName" value="<?=$form->firstName?>">
        <?php ValidationUtil::renderFieldErrors($form, 'firstName'); ?>
    </div>
    <div class="form-group">
        <label for="secondName">Second name</label>
        <input type="text" name="secondName" class="form-control" id="secondName" value="<?=$form->secondName?>">
        <?php ValidationUtil::renderFieldErrors($form, 'secondName'); ?>
    </div>
    <div class="form-group">
        <label for="phoneNumber">Phone number</label>
        <input type="text" name="phoneNumber" class="form-control" id="phoneNumber" value="<?=$form->phoneNumber?>">
        <?php ValidationUtil::renderFieldErrors($form, 'phoneNumber'); ?>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" id="email" value="<?=$form->email?>">
        <?php ValidationUtil::renderFieldErrors($form, 'email'); ?>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <div class="preview">
            <? if($form->photo):?>
                <img src="<?=$imagesRoot . $form->photo?>" width="150" height="150" />
            <?php endif;?>
        </div>
        <input type="hidden" name="photo" id="image" value="<?=$form->photo?>">
        <input type="file" style="display: none" class="form-control" id="file-input" value="<?=$form->photo?>" accept="image/*">
        <button type="button" class="btn btn-primary" id="select-image">Choose image</button>
        <?php ValidationUtil::renderFieldErrors($form, 'image'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>