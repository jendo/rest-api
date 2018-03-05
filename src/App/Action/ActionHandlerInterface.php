<?php
namespace MyApp\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ActionHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     * @return mixed
     */
    public function __invoke(ServerRequestInterface  $request, ResponseInterface $response, array $arguments = []);
}
