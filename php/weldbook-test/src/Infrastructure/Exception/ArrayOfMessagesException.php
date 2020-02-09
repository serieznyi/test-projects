<?php declare(strict_types=1);

namespace App\Infrastructure\Exception;

/**
 * Convert exception to array of messages.
 * One exception can contains few error messages
 *
 * @package App\Infrastructure\Exception
 */
interface ArrayOfMessagesException
{
    public function toArrayOfMessages(): array;
}