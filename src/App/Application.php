<?php
namespace MyApp;

use MyApp\Slim\ApplicationFactory;
use MyApp\Slim\SlimApp;

class Application
{
    /**
     * @var SlimApp
     */
    private $slimApplication;

    /**
     * @param ApplicationFactory $slimAppFactory
     */
    public function __construct(ApplicationFactory $slimAppFactory)
    {
        $this->slimApplication = $slimAppFactory->create();
    }

    public function run()
    {
        $this->slimApplication->run();
    }
}
