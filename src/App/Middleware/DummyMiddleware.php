<?php
namespace MyApp\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DummyMiddleware implements MiddlewareInterface
{

    /**
     * Execute this middleware.
     *
     * @param  ServerRequestInterface $request The PSR7 request.
     * @param  ResponseInterface $response The PSR7 response.
     * @param  callable $next The Next middleware.
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');

        return $response;
    }
}
