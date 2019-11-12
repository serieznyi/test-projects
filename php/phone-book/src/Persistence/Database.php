<?php

namespace App\Persistence;

use PDO;

/**
 * Class Database
 * @package App\Persistence
 */
class Database
{
    private static $instances = [];

    /**
     * @var PDO
     */
    private $connection;

    /**
     * @param string $scheme
     * @param string $user
     * @param string $password
     * @return Database
     */
    public static function instance($scheme, $user, $password) {
        $key = "$scheme:$user:$password";

        if (!array_key_exists($key, static::$instances)) {
            self::$instances[$key] = new self($scheme, $user, $password);
        }

        return self::$instances[$key];
    }

    /**
     * Database constructor.
     * @param string $scheme
     * @param string $user
     * @param string $pass
     */
    private function __construct($scheme, $user, $pass)
    {
        $this->connection = new PDO($scheme, $user, $pass);
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    /**
     * @param string $login
     * @return array
     */
    public function findUserByLogin($login) {
        return $this->findOneBy('user', ['login' => $login]);
    }

    /**
     * @param string $email
     * @return array
     */
    public function findUserByEmail($email)
    {
        return $this->findOneBy('user', ['email' => $email]);
    }

    /**
     * @param $email
     * @param $login
     * @param $hashPassword
     * @return void
     */
    public function insertUser($email, $login, $hashPassword)
    {
        $sql = 'INSERT INTO user (email, login, password_hash) VALUES (?,?,?)';

        $this
            ->connection
            ->prepare($sql)
            ->execute([$email, $login, $hashPassword])
        ;
    }

    /**
     * @param string $login
     * @param string $cryptPassword
     * @return array
     */
    public function findUserByLoginAndPassword($login, $cryptPassword)
    {
        $stat = $this->connection->prepare(
            'SELECT * FROM user WHERE (email = :login or login = :login) and password_hash = :pass'

        );

        $stat->execute([':login' => $login, ':pass' => $cryptPassword]);

        $data = $stat->fetch();

        return $data === false ? [] : $data;
    }

    /**
     * @param int $phone
     * @param string $userId
     * @return array
     */
    public function findBookRowByPhone($phone, $userId)
    {
        return $this->findOneBy('phone', ['phone_number' => $phone, 'user_id' => $userId]);
    }

    /**
     * @param string $email
     * @param string $userId
     * @return array
     */
    public function findBookRowByEmail($email, $userId)
    {
        return $this->findOneBy('phone', ['email' => $email, 'user_id' => $userId]);
    }

    /**
     * @param string $firstName
     * @param string $secondName
     * @param string $email
     * @param int $phoneNumber
     * @param $image
     * @param int $userId
     */
    public function insertBookRow($firstName, $secondName, $email, $phoneNumber, $image, $userId)
    {
        $sql = 'INSERT INTO phone (first_name, second_name, phone_number, email, user_id, photo) VALUES (
            :first_name, :second_name, :phone, :email, :user_id, :photo
        )';

        $this
            ->connection
            ->prepare($sql)
            ->execute([
                'first_name' => $firstName,
                'second_name' => $secondName,
                'email' => $email,
                'photo' => $image,
                'phone' => $phoneNumber,
                'user_id' => $userId,
            ])
        ;
    }

    /**
     * @param string $userId
     * @return array|mixed
     */
    public function findAllPhones($userId)
    {
        return $this->findAllBy('phone', ['user_id' => $userId]);
    }

    /**
     * @param string $id
     * @param int $userId
     */
    public function deletePhoneById($id, $userId)
    {
        $sql = 'DELETE FROM phone WHERE id = :id and user_id = :user';

        $this
            ->connection
            ->prepare($sql)
            ->execute([
                'id' => $id,
                'user' => $userId,
            ])
        ;
    }

    private function findOneBy($table, $params) {
        $stat = $this->findBy($table, $params);

        $data = $stat->fetch();

        return $data === false ? [] : $data;
    }

    private function findAllBy($table, $params) {
        $stat = $this->findBy($table, $params);

        $data = $stat->fetchAll();

        return $data === false ? [] : $data;
    }

    /**
     * @param string $tableName
     * @param array $params
     * @return \PDOStatement
     */
    private function findBy($tableName, $params)
    {
        $query = "SELECT * FROM {$tableName} WHERE ";

        $execParams = [];
        foreach ($params as $name => $value) {
            if ($execParams) {
                $query .= ' AND ';
            }

            $query .= "$name = :$name";
            $execParams[":$name"] = $value;
        }

        $stat = $this->connection->prepare($query);
        $stat->execute($execParams);

        return $stat;
    }

    /**
     * @param string $id
     * @return array
     */
    public function findPhoneById($id)
    {
        return $this->findOneBy('phone', ['id' => $id]);
    }

    /**
     * @param int $id
     * @param string $firstName
     * @param string $secondName
     * @param string $email
     * @param int $phoneNumber
     * @param string $photo
     * @param string $userId
     */
    public function updateBookRow($id, $firstName, $secondName, $email, $phoneNumber, $photo, $userId)
    {
        $sql = 'UPDATE phone 
                SET first_name = :first_name, second_name = :second_name,  
                phone_number = :phone_number, photo = :photo, email = :email
                WHERE user_id = :user_id and id = :id      
        ';

        $this
            ->connection
            ->prepare($sql)
            ->execute([
                'first_name' => $firstName,
                'second_name' => $secondName,
                'email' => $email,
                'photo' => $photo,
                'phone_number' => $phoneNumber,
                'user_id' => $userId,
                'id' => $id,
            ])
        ;
    }
}