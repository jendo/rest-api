<?php
namespace MyApp\Action;

use MyApp\Response\ResponseStatus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Action implements ActionHandlerInterface
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

        return $response->withStatus(ResponseStatus::S200_OK);
    }
}
