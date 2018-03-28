<?php
namespace MyApp\Api\GameResult\Get;

use MyApp\Action\ActionHandlerInterface;
use MyApp\Api\GameResult\QueryParams;
use MyApp\Exception\GameResultNotFoundException;
use MyApp\Redis\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use MyApp\Response\ResponseStatus;

class GetAction implements ActionHandlerInterface
{
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
        $gameId = $arguments[QueryParams::GAME_ID];

        try {
            $data = $this->redisRepository->getGameResult($gameId);
            $responseBody = $response->getBody();
            $responseBody->write(\GuzzleHttp\json_encode($data));
            $response = $response->withBody($responseBody);
            $status = ResponseStatus::S200_OK;
        } catch (GameResultNotFoundException $e) {
            $status = ResponseStatus::S404_NOT_FOUND;
        }

        return $response->withStatus($status);
    }
}
