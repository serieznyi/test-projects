<?php


namespace App\Action\Api;


use App\Adapter\RequestAdapter;
use App\Form\Api\ConvertForm;
use App\Service\Currency\Converter\DefaultCurrencyConverter;
use yii\base\Action;

class ConvertIndex extends Action
{
    /**
     * @var DefaultCurrencyConverter
     */
    private $convertService;
    /**
     * @var RequestAdapter
     */
    private $requestAdapter;

    public function __construct(
        $id,
        $controller,
        RequestAdapter $requestAdapter,
        DefaultCurrencyConverter $convertService,
        $config = []
    )
    {
        $this->convertService = $convertService;
        $this->requestAdapter = $requestAdapter;

        parent::__construct($id, $controller, $config);
    }

    public function run()
    {
        $form = new ConvertForm();

        $form->setAttributes($this->requestAdapter->post());

        if (!$form->validate()) {
            return $form->getErrors();
        }

        $value = $this->convertService->convert(
            DefaultCurrencyConverter::DEFAULT_SOURCE_CURRENCY_CODE,
            $form->currency,
            $form->value
        );

        return [
            'currency' => $form->currency,
            'value' => $value,
        ];
    }
}