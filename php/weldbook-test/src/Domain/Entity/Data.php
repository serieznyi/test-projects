<?php declare(strict_types=1);

namespace App\Domain\Entity;

final class Data
{
    private string $typeOfJoint;

    private string $mainMaterial;

    private int $id;

    private array $extraFields;

    public function __construct(int $id, string $mainMaterial, string $typeOfJoint, array $extraFields)
    {
        $this->id = $id;
        $this->mainMaterial = $mainMaterial;
        $this->typeOfJoint = $typeOfJoint;

        ksort($extraFields);

        $this->extraFields = $extraFields;
    }

    public function getTypeOfJoint(): string
    {
        return $this->typeOfJoint;
    }

    public function getMainMaterial(): string
    {
        return $this->mainMaterial;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExtraFields(): array
    {
        return $this->extraFields;
    }
}