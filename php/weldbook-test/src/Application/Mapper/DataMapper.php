<?php declare(strict_types=1);

namespace App\Application\Mapper;

use App\Domain\Entity\Data;
use ReflectionObject;

class DataMapper
{
    /**
     * @param Data $entity
     * @param array $row
     * @throws \ReflectionException
     */
    public function mapData(Data $entity, array $row): void
    {
        $refObject = new ReflectionObject($entity);

        $id = $refObject->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($entity, (int)$row['id']);

        $mainMaterial = $refObject->getProperty('mainMaterial');
        $mainMaterial->setAccessible(true);
        $mainMaterial->setValue($entity, $row['pipeline-MAIN_MATERIAL']);

        $typeOfJoint = $refObject->getProperty('typeOfJoint');
        $typeOfJoint->setAccessible(true);
        $typeOfJoint->setValue($entity, $row['welding-TYPE_OF_JOINT']);

        $fields = array_filter(
            $row,
            static function ($key) {
                return !in_array($key, ['id', 'pipeline-MAIN_MATERIAL', 'welding-TYPE_OF_JOINT'], true);
            },
            ARRAY_FILTER_USE_KEY
        );

        $fields = array_map(static function ($value) {
            return is_numeric($value) ? (double)$value : $value;
        }, $fields);

        $extra = $refObject->getProperty('extraFields');
        $extra->setAccessible(true);
        $extra->setValue($entity, $fields);

    }
}