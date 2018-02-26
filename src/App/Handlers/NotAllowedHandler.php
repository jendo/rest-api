<?php
namespace MyApp\Handlers;

use MyApp\Response\ResponseStatus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

class NotAllowedHandler
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $methods
     * @return static
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, array $methods)
    {
        $message = ['error' => ['message' => 'Method not allowed. Must be one of: ' . implode(',', $methods)]];

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write(\GuzzleHttp\json_encode($message));

        return $response
            ->withStatus(ResponseStatus::S405_METHOD_NOT_ALLOWED)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }
}
