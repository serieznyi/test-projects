<?php
declare(strict_types=1);

namespace App\Exception;

use Throwable;

/**
 * Class DomainException.
 *
 * @package common\exception
 */
class ModelValidationException extends DomainException
{
    /**
     * @var array Детальная информация об исключении
     */
    protected $details;

    /**
     * DomainException constructor.
     *
     * @param string $message
     * @param Throwable|null $previous
     * @param array $details
     */
    public function __construct(
        $message = '',
        Throwable $previous = null,
        array $details = []
    ) {
        $this->details = $details;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param array $details
     *
     * @return DomainException
     */
    public static function createDefault(array $details): DomainException
    {
        return new self('Не могу сохранить модель', null, $details);
    }
}
