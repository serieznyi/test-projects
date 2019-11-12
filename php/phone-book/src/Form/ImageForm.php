<?php

namespace App\Form;

/**
 * Class ImageForm
 * @package App\Form
 */
class ImageForm extends Form
{
    public $name;
    public $path;
    public $type;

    public function __construct()
    {
        $this->registerRules();
    }

    public function load($data)
    {
        if (!array_key_exists('image', $data)) {
            return;
        }

        $image = $data['image'];

        $this->name = $image['name'];
        $this->type = $image['type'];
        $this->path = $image['tmp_name'];
    }

    private function registerRules()
    {
        $this->addRule(function () {
            if (!$this->path) {
                $this->addError('path', 'Image is empty');
            }
        });
    }
}