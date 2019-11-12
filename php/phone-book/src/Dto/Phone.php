<?php

namespace App\Dto;

/**
 * Class Phone
 * @package App\Dto
 */
class Phone
{
    private $firstName;
    private $secondName;
    private $phone;
    private $email;
    private $id;
    private $photo;

    /**
     * Phone constructor.
     * @param string $id
     * @param string $firstName
     * @param string $secondName
     * @param string $phone
     * @param string $email
     */
    public function __construct($id, $firstName, $secondName, $phone, $email, $photo)
    {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->phone = $phone;
        $this->email = $email;
        $this->id = $id;
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}