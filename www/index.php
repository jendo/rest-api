<?php
use MyApp\Application;
use Nette\DI\Container;

/** @var Container $container */
$container = require __DIR__ . '/../src/bootstrap.php';

/** @var Application $application */
$application = $container->getService('application');
$application->run();
