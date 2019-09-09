<?php
declare(strict_types=1);

namespace App;
use App\Helper\ArrayHelper;
use function array_key_exists;
use Assert\Assertion;
use function count;
use function in_array;
use function is_array;
use function is_string;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;


class EventConfigurator extends Component implements BootstrapInterface
{
    /**
     * Гарантирует, что компонент инициализируется только один раз.
     * Повторый вызов setRules ни к чему не приведет
     *
     * @var bool
     */
    private $initialized = false;
    /**
     * @var array
     */
    private $rules;
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app): void
    {
        $this->bindAllRules();
    }
    /**
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->sortRules($rules);
        $this->rules = $rules;
    }
    public function offAll(): void
    {
        Event::offAll();
        $this->initialized = false;
    }
    public function bindSafeRules(): void
    {
        if ($this->initialized) {
            return;
        }
        $rulesForBind = [];
        foreach ($this->rules as $key => $rule) {
            if ($ignoreException = ArrayHelper::getValue($rule, 'ignoreException')) {
                $rulesForBind[] = $rule;
            }
        }
        $this->bindRules($rulesForBind);
        $this->initialized = true;
    }
    public function bindAllRules(): void
    {
        if ($this->initialized) {
            return;
        }
        $this->bindRules($this->rules);
        $this->initialized = true;
    }
    /**
     * Валидирует правила и добавляет их в менеджер событий.
     *
     * @param array $rules
     */
    private function bindRules(array $rules): void
    {
        foreach ($rules as $rule) {
            Assertion::keyExists($rule, 'target', 'Отправитель события не указан');
            Assertion::string($rule['target'], 'Отправителем события должен быть className');
            Assertion::keyExists($rule, 'event', 'Название события не указано');
            Assertion::keyExists($rule, 'listener', 'Слушатель не указан');
            if (array_key_exists('app', $rule) && !in_array(true, $rule['app'], true)) {
                continue;
            }
            $this->bindRule($rule);
        }
    }
    private function sortRules(array &$rules): void
    {
        $count = count($rules);
        foreach ($rules as $key => $rule) {
            $rules[$key]['priority'] = $rules[$key]['priority'] ?? ($count - $key);
        }
        ArrayHelper::sortByKey($rules, 'priority', SORT_DESC);
    }
    private function bindRule(array $rule): void
    {
        foreach ((array) $rule['event'] as $eventName) {
            Event::on(
                $rule['target'],
                $eventName,
                $this->createListener($rule)
            );
        }
    }
    private function createListener(array $rule): callable
    {
        $method = $rule['method'] ?? '__invoke';
        $listener = $rule['listener'];
        return function ($event) use ($listener, $method) {
            if (is_string($listener) || is_array($listener)) {
                /**
                 * @var callable $eventListener
                 */
                $eventListener = Yii::createObject($listener);
                $callable = [$eventListener, $method];
            } else {
                $callable = $listener;
            }
            return $callable($event);
        };
    }
}