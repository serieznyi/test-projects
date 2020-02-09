<?php declare(strict_types=1);

namespace Test\Unit\App\Application;

use App\Application\Mapper\DataMapper;
use App\Domain\Entity\Data;
use Codeception\Test\Unit;
use UnitTester;

class DataMapperTest extends Unit
{
    protected UnitTester $tester;
    
    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    public function testWhatMapperIsCorrectMapData(): void
    {
        $mapper = new DataMapper();

        $entity = new Data(1, '', '', []);

        $mapper->mapData($entity, [
            'id' => 1,
            'pipeline-MAIN_MATERIAL' => 'LTCS',
            'welding-TYPE_OF_JOINT' => 'F',
            'pipeline-LINE_NUMBER' => '123-IA-86506_01',
        ]);

        $this->assertEquals(1, $entity->getId());
        $this->assertEquals('F', $entity->getTypeOfJoint());
        $this->assertEquals('LTCS', $entity->getMainMaterial());

        $this->assertArrayHasKey('pipeline-LINE_NUMBER', $entity->getExtraFields());
        $this->assertEquals('123-IA-86506_01', $entity->getExtraFields()['pipeline-LINE_NUMBER']);
    }
}