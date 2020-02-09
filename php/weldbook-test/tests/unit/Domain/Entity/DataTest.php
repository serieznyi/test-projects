<?php declare(strict_types=1);

namespace Test\Unit\Domain\Entity;

use App\Application\DataSet;
use App\Domain\Entity\Data;
use Codeception\Test\Unit;
use UnitTester;

class DataTest extends Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    public function testWhatDataCorrectSortExtraFields(): void
    {
        $entity1 = new Data(
            1,
            'LTCS',
            'F',
            ['sda' => 1, 'pipeline-LINE_NUMBER' => '123-IA-86506_01', 'asd' => 1]
        );

        $entity2 = new Data(
            1,
            'LTCS',
            'F',
            ['sda' => 1, 'asd' => 1, 'pipeline-LINE_NUMBER' => '123-IA-86506_01']
        );

        $this->assertSame($entity1->getExtraFields(), $entity2->getExtraFields(), 'Extra is same');
    }
}