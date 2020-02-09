<?php declare(strict_types=1);

namespace App\Application\Form;

use App\Infrastructure\Form;

final class DataForm extends Form
{
    private const DEFAULT_PAGE = 1;

    private const DEFAULT_LIMIT = 10;

    private int $page = self::DEFAULT_PAGE;

    private int $limit = self::DEFAULT_LIMIT;

    private array $fieldsForValidate = [];

    private array $filters = [];

    public function __construct()
    {
        $this->registerRules();
    }

    private function registerRules(): void
    {
        $this->addRule(function () {
            $value = $this->fieldsForValidate['page'];

            if ($value) {
                if (!is_numeric($value)) {
                    $this->addError(
                        'page',
                        'Страница должна быть числом'
                    );
                } else {
                    $this->page = (int)$value;
                }
            }
        });

        $this->addRule(function () {
            $value = $this->fieldsForValidate['limit'];

            if ($value) {
                if (!is_numeric($value)) {
                    $this->addError(
                        'limit',
                        'Лимит должнен быть числом'
                    );
                } else {
                    $this->limit = (int)$value;
                }
            }
        });
    }

    public function load(array $data): void
    {
        $this->fieldsForValidate = [
            'page' => $data['page'] ?? null,
            'limit' => $data['limit'] ?? null,
        ];

        unset($data['limit'], $data['page']);

        $this->filters = $data;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}