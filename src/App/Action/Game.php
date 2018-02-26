<?php
namespace MyApp\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Game implements ActionHandlerInterface
{

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = [])
    {
        $responseBody = $response->getBody();

        $data = array('name' => 'Bob', 'age' => 40);
        $responseBody->write(json_encode($data));

        return $response->withStatus('asd');
    }
}
