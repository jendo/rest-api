<?php

namespace MyApp\Redis;

use MyApp\Action\Game\GameApiFields;
use Predis\Client;
use Predis\Collection\Iterator;

class Repository
{
    const GAME_RESULT_PREFIX = 'g_result';
    const USER_PREFIX = 'user';

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
