<?php
declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use yii\web\Application;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

require __DIR__ . '/../../vendor/autoload.php';

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__ . '/../../.env');

\defined('YII_DEBUG') or \define('YII_DEBUG', \Helpers\getenv('YII_DEBUG'));
\defined('YII_ENV') or \define('YII_ENV', \Helpers\getenv('YII_ENV'));
\defined('YII_ENV_LOCAL') or \define('YII_ENV_LOCAL', YII_ENV === 'local');

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../../config/bootstrap.php';

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/../../config/*.{main,local}.php'),
    new PhpFileProvider(__DIR__ . '/../../config/api/*.{main,local}.php'),
]);

$application = new Application($aggregator->getMergedConfig());
$application->run();
