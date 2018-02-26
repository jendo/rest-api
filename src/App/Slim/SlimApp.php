<?php
namespace MyApp\Slim;

use Psr\Http\Message\ResponseInterface;
use Slim\App;

class SlimApp extends App
{
    /**
     * @param ResponseInterface $response
     */
    public function respond(ResponseInterface $response)
    {
        $slimSettings = $this->getContainer()->get('settings');

        $response = $response->withHeader('Content-Type', $slimSettings['defaultContentType']);
        parent::respond($response);
    }

}
