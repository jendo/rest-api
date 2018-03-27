<?php

namespace MyApp\Redis;

use MyApp\Action\Game\GameApiFields;
use MyApp\Api\User\UserFields;
use MyApp\Exception\UserNotFoundException;
use Predis\Client;
use Predis\Collection\Iterator;
use Predis\Response\Status;

class Repository
{
    const SET_STATUS_OK = 'OK';

    const GAME_RESULT_PREFIX = 'g_result';
    const USER_PREFIX = 'user';

    const FIELD_USER_ID = 'userId';
    const FIELD_USER_NAME = 'name';
    const FIELD_USER_SURNAME = 'surname';

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param array $input
     * @return bool
     */
    public function saveUser(array $input): bool
    {
        $userId = $input[UserFields::USER_ID];
        $name = $input[UserFields::NAME];
        $surname = $input[UserFields::SURNAME];

        $userKey = self::getUserKey($userId);

        /** @var Status $status */
        $status = $this->redisClient->hmset(
            $userKey,
            [
                self::FIELD_USER_ID => $userId,
                self::FIELD_USER_NAME => $name,
                self::FIELD_USER_SURNAME => $surname,
            ]
        );

        return $status->getPayload() === self::SET_STATUS_OK;
    }

    /**
     * @param int $userId
     * @return array
     * @throws UserNotFoundException
     */
    public function getUserData(int $userId)
    {
        $userKey = self::getUserKey($userId);
        $data = $this->redisClient->hgetall($userKey);

        if ($data === []) {
            throw new UserNotFoundException();
        }

        return $data;
    }

    /**
     * @param array $input
     * @return int
     */
    public function saveGameResult(array $input): int
    {
        $userId = $input[GameApiFields::USER_ID];
        $gameId = $input[GameApiFields::GAME_ID];
        $score = $input[GameApiFields::SCORE];

        $status = $this->redisClient->zadd(self::getGameResultKey($gameId), $score, self::getUserKey($userId));

        return $status;
    }

    /**
     * @param int $gameId
     * @return array
     */
    public function getGameResult(int $gameId): array
    {
        $data = $this->redisClient->zrange(self::getGameResultKey($gameId), 0, 10, ['withScores' => TRUE]);

        return $data;
    }

    /**
     * @param int $gameId
     * @return string
     */
    private static function getGameResultKey(int $gameId): string
    {
        return sprintf('%s:%u', self::GAME_RESULT_PREFIX, $gameId);
    }

    /**
     * @param int $userId
     * @return string
     */
    private static function getUserKey(int $userId): string
    {
        return sprintf('%s:%u', self::USER_PREFIX, $userId);
    }
}
