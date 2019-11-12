<?php

namespace App\Action\Image;

use App\Form\ImageForm;
use App\Service\PhoneImageService;
use App\Web\Request;

/**
 * Class UploadAction
 * @package App\Action\File
 */
class UploadAction
{
    /**
     * @var PhoneImageService
     */
    private $fileService;

    public function __construct(PhoneImageService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function __invoke(Request $request)
    {
        $form = new ImageForm();

        $form->load($request->getFiles());


        if ($request->isPost() && $form->validate()) {
            $fileInfo = $this->fileService->saveInTemp($form);

            header('Content-type: application/json');
            return json_encode([
                'success' => true,
                'data' => $fileInfo,
            ]);
        }

        header('Content-type: application/json');
        return json_encode([
            'success' => false,
            'data' => [
                'errors' => $form->getErrors(),
            ],
        ]);
    }
}