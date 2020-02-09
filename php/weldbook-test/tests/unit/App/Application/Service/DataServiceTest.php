<?php declare(strict_types=1);

namespace Test\Unit\App\Application\Service;

use App\Application\Form\DataForm;
use App\Application\Service\DataService;
use App\Domain\Entity\Data;
use App\Domain\Repository\DataRepository;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use UnitTester;

class DataServiceTest extends Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    public function testWhatServiceReturnCorrectDataSet(): void
    {
        /** @var DataRepository|MockObject $dataRepository */
        $dataRepository = $this
            ->getMockBuilder(DataRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $dataRepository
            ->expects($this->any())
            ->method('findAllBy')
            ->willReturn([
                new Data(1, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
                new Data(2, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
                new Data(3, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
                new Data(4, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
                new Data(5, 'LTCS', 'F', ['pipeline-LINE_NUMBER' => '123-IA-86506_01']),
            ])
        ;

        $dataRepository
            ->expects($this->any())
            ->method('count')
            ->willReturn(5)
        ;

        $dataService = new DataService($dataRepository);

        $form = new DataForm();

        $dataSet = $dataService->findData($form);

        $this->assertNotEmpty($dataSet->jsonSerialize(), 'Service return data');
    }
}