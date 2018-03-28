<?php
namespace MyApp\Middleware;

use MyApp\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class ValidatorMiddleware implements MiddlewareInterface
{

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @inheritdoc
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->request = $request;

        $errors = $this->getErrors($request, $response);
        if (count($errors) > 0) {
            throw new BadRequestException($errors);
        }

        return $next($this->request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract protected function getErrors(ServerRequestInterface $request, ResponseInterface $response);

}
