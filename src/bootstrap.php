<?php
use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__DIR__);
require_once $baseDir . '/vendor/autoload.php';

$loader = new ContainerLoader($baseDir . '/temp', $autoRebuild = true);
$class = $loader->load(
    function (Compiler $compiler) use ($baseDir) {
        $compiler->addConfig(['parameters' => ['baseDir' => $baseDir]]);
        $compiler->loadConfig($baseDir . '/src/config/config.neon');
        $compiler->loadConfig($baseDir . '/src/config/api.neon');
    }
);

return new $class;
