<?php

namespace MyApp\Action\User\Create;

use MyApp\Action\ActionHandlerInterface;
use MyApp\Redis\Repository;
use MyApp\Response\ResponseStatus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateAction implements ActionHandlerInterface
{

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
    {
        $input = $request->getParsedBody();

        $result = $this->repository->saveUser($input);

        $responseStatus = $result ? ResponseStatus::S201_CREATED : ResponseStatus::S500_INTERNAL_SERVER_ERROR;

        return $response->withStatus($responseStatus);
    }
}
