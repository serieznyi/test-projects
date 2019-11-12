<?php

namespace App\Service;

use App\Dto\Phone;
use App\Form\BookForm;
use App\Persistence\Database;

/**
 * Class PhoneService
 * @package App\Service
 */
class PhoneService
{
    /**
     * @var Database
     */
    private $database;
    /**
     * @var AuthService
     */
    private $authService;
    /**
     * @var PhoneImageService
     */
    private $fileService;

    public function __construct(Database $database, AuthService $authService, PhoneImageService $fileService)
    {
        $this->database = $database;
        $this->authService = $authService;
        $this->fileService = $fileService;
    }

    public function add(BookForm $form) {
        $this->database->insertBookRow(
            $form->firstName,
            $form->secondName,
            $form->email,
            $form->phoneNumber,
            $form->photo,
            $this->authService->getUserIdentifier()
        );

        $this->fileService->moveFileInStorage($form->photo);
    }

    /**
     * @param int $identifier
     */
    public function deleteByIdentifier($identifier) {
        $this->database->deletePhoneById($identifier, $this->authService->getUserIdentifier());
    }

    /**
     * @param int $id
     * @param BookForm $form
     */
    public function update($id, BookForm $form) {
        $this->database->updateBookRow(
            $id,
            $form->firstName,
            $form->secondName,
            $form->email,
            $form->phoneNumber,
            $form->photo,
            $this->authService->getUserIdentifier()

        );

        $this->fileService->moveFileInStorage($form->photo);
    }

    /**
     * @return array
     */
    public function findAllPhones()
    {
        return array_map(
            static function($row){
                return new Phone(
                    $row['id'],
                    $row['first_name'],
                    $row['second_name'],
                    $row['phone_number'],
                    $row['email'],
                    $row['photo']
                );
            },
            $this->database->findAllPhones($this->authService->getUserIdentifier()));
    }
}