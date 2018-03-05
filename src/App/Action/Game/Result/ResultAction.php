<?php
namespace MyApp\Action\Game\Result;

use MyApp\Action\ActionHandlerInterface;
use MyApp\Redis\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use MyApp\Response\ResponseStatus;

class ResultAction implements ActionHandlerInterface
{
    const ARG_GAME_ID = 'id';

    /**
     * @var Repository
     */
    private $redisRepository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->redisRepository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     * @return static
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
    {
        $gameId = $arguments[self::ARG_GAME_ID];

        $data = $this->redisRepository->getGameResult($gameId);

        $responseBody = $response->getBody();
        $responseBody->write(\GuzzleHttp\json_encode($data));

        $newResponse = $response->withBody($responseBody);
        return $newResponse->withStatus(ResponseStatus::S200_OK);
    }
}
