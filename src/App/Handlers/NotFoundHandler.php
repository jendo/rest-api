<?php
namespace MyApp\Handlers;

use MyApp\Response\ResponseStatus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

class NotFoundHandler
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return static
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $message = ['error' => ['message' => 'Page not found']];

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write(\GuzzleHttp\json_encode($message));

        return $response
            ->withStatus(ResponseStatus::S404_NOT_FOUND)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }
}
