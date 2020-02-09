<?php declare(strict_types=1);

namespace Test\Unit\App\Application;

use App\Application\DataSet;
use App\Domain\Entity\Data;
use Codeception\Test\Unit;
use UnitTester;

class DataSetTest extends Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    public function testWhatDataSetContainsCorrectData(): void
    {
        $data = [
            new Data(1, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
        ];

        $dataSet = new DataSet($data);

        $dataSetData = $dataSet->jsonSerialize();

        $this->assertArrayHasKey('head', $dataSetData, 'Head exists');
        $this->assertArrayHasKey('body', $dataSetData, 'Data exists');

        $this->assertEquals(
            ['id', 'pipeline-MAIN_MATERIAL', 'welding-TYPE_OF_JOINT', 'pipeline-LINE_NUMBER'],
            $dataSetData['head'],
            'Head is correct'
        );

        $this->assertEquals(
            [
                [1, 'LTCS', 'F', '123-IA-86506_01'],
            ],
            $dataSetData['body'],
            'Body is correct'
        );
    }
}