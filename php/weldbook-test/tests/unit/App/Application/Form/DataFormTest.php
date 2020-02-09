<?php declare(strict_types=1);

namespace Test\Unit\App\Application\Form;

use App\Application\Form\DataForm;
use App\Domain\Repository\DataSearchCriteria;
use Codeception\Test\Unit;
use UnitTester;

class DataFormTest extends Unit
{
    protected UnitTester $tester;
    
    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    public function testWhatFormHaveDefaultPageAndLimit(): void
    {
        $form = new DataForm();
        $form->validate();

        $this->assertEquals(1, $form->getPage());
        $this->assertEquals(10, $form->getLimit());
    }

    public function testWhatFormCorrectValidate(): void
    {
        $form = new DataForm();
        $form->load($this->getFormData());
        $form->validate();

        $this->assertEquals(11, $form->getPage(), 'Page is correct');
        $this->assertEquals(55, $form->getLimit(), 'Limit is correct');
    }

    public function testWhatFormCreateFilters(): void
    {
        $form = new DataForm();
        $form->load($this->getFormData());
        $form->validate();

        $filters =  $form->getFilters();
        $this->assertArrayHasKey('pipeline-MAIN_MATERIAL', $filters, 'Has real filed');
        $this->assertArrayHasKey('fake-field' , $filters, 'Has fake field');
    }

    private function getFormData(): array
    {
        return [
            'limit' => 55,
            'page' => 11,
            'pipeline-MAIN_MATERIAL' => 'LTCS',
            'fake-field' => 'asd2',
        ];
    }
}