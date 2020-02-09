<?php declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DataSet;
use App\Application\Form\DataForm;
use App\Domain\Repository\DataRepository;

final class DataService
{
    /**
     * @var DataRepository
     */
    private DataRepository $dataRepository;

    public function __construct(DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    public function findData(DataForm $form): DataSet
    {
        $data = $this->dataRepository->findAllBy(
            $form->getFilters(),
            $form->getPage(),
            $form->getLimit()
        );

        return new DataSet($data);
    }
}