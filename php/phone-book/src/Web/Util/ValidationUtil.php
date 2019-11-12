<?php

namespace App\Web\Util;

use App\Form\Form;

class ValidationUtil
{
    /**
     * @param Form $form
     * @param string $fieldName
     */
    public static function renderFieldErrors($form, $fieldName) {
        if (!$form->hasErrors()) {
            return;
        }

        $errors = $form->getErrors();

        if (empty($errors[$fieldName])) {
            return;
        }

        foreach ($errors[$fieldName] as $error) {
            echo "<small class='form-text text-danger'>{$error}</small>";
        }
    }
}