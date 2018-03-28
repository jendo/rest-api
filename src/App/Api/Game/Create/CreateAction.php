<?php
namespace MyApp\Api\Game\Create;

use MyApp\Action\ActionHandlerInterface;
use MyApp\Api\GameResult\GameResultStatus;
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
     * @return static
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
    {
        $input = $request->getParsedBody();

        $result = $this->repository->saveGameResult($input);
        $responseStatus = $this->getResponseStatusByGameResult($result);

        return $response->withStatus($responseStatus);
    }

    /**
     * @param int $gameResult
     * @return int
     */
    private function getResponseStatusByGameResult(int $gameResult): int
    {
        switch ($gameResult) {
            case GameResultStatus::SAVED:
                $status = ResponseStatus::S201_CREATED;
                break;
            case GameResultStatus::ALREADY_SAVED:
                $status = ResponseStatus::S304_NOT_MODIFIED;
                break;
            default:
                $status = ResponseStatus::S500_INTERNAL_SERVER_ERROR;
        };

        return $status;
    }
}
