<?php

use Predis\Client;

require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
    // Uncomment for debugging
    //echo 'Looking for class ' . $class . "\n";

    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require($file);
    }
});

$builder = new \DI\ContainerBuilder();
$settings = new \Config\Settings();

$applicationContext = $builder->build();

// region Doctrine
$databaseConfig = \Doctrine\ORM\Tools\Setup::createConfiguration($settings->loggingEnvironment === 'production');
$driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(new \Doctrine\Common\Annotations\AnnotationReader(), [__DIR__ . '/DataAccess/Models']);
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
$databaseConfig->setMetadataDriverImpl($driver);
$databaseConnection = [
    'driver' => 'mysqli',
    'user' => $settings->databaseUser,
    'password' => $settings->databasePassword,
    'host' => $settings->databaseHost,
    'dbname' => $settings->databaseName,
    'charset' => 'utf8mb4'
];
$entityManager = \Doctrine\ORM\EntityManager::create($databaseConnection, $databaseConfig);
$applicationContext->set(\Doctrine\ORM\EntityManager::class, $entityManager);
// endregion

// region Redis
if ($settings->useRedis) {
    $redis = new Predis\Client([
        'scheme' => 'tcp',
        'host' => $settings->redisHost,
        'port' => $settings->redisPort
    ]);
    $applicationContext->set(Client::class, $redis);
} else {
    $applicationContext->set(Client::class, null);
}
// endregion

if ($settings->loggingEnvironment !== 'development') {
    // TODO Rollbar configuration
    \Rollbar\Rollbar::init([
        'access_token' => $settings->loggingAccessToken,
        'environment' => $settings->loggingEnvironment
    ]);
}

ini_set('max_execution_time', 300);