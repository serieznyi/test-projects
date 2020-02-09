<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\Entity\Data;

use JsonSerializable;

final class DataSet implements JsonSerializable
{
    /**
     * @var Data[]
     */
    private array $data;

    /**
     * @param Data[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return [
            'head' => $this->getColumns(),
            'body' => $this->getBody(),
        ];
    }

    private function getColumns()
    {
        if (!$this->data) {
            return [];
        }

        $dataItem = current($this->data);

        return array_merge(
            [
                'id',
                'pipeline-MAIN_MATERIAL',
                'welding-TYPE_OF_JOINT',
            ],
            array_keys($dataItem->getExtraFields())
        );
    }

    private function getBody(): array
    {
        return array_map(
            static function (Data $entity) {
                return array_merge([
                    $entity->getId(),
                    $entity->getMainMaterial(),
                    $entity->getTypeOfJoint(),
                ], array_values($entity->getExtraFields()));
            },
            $this->data
        );
    }
}