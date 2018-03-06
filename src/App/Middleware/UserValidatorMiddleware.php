<?php
namespace MyApp\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserValidatorMiddleware extends ValidatorMiddleware
{

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return array
     */
    protected function getErrors(ServerRequestInterface $request, ResponseInterface $response)
    {
        $input = $request->getParsedBody();

        if ($input === null) {
            return ['Body must not be empty.'];
        }
    }
}
