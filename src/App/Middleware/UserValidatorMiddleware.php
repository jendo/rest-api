<?php
namespace MyApp\Middleware;

use MyApp\Action\User\UserApiFields;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserValidatorMiddleware implements MiddlewareInterface
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $input = $request->getParsedBody();
        $userId = $input[UserApiFields::USER_ID];

        return $next($request, $response);
    }
}
