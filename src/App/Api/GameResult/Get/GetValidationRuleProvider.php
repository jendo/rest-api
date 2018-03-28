<?php
namespace MyApp\Api\GameResult\Get;

use MyApp\Api\GameResult\QueryParams;
use MyApp\Validation\Custom\PositiveInteger;
use MyApp\Validation\ValidationRuleProviderInterface;
use Symfony\Component\Validator\Constraints;

class GetValidationRuleProvider implements ValidationRuleProviderInterface
{
    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            QueryParams::GAME_ID => $this->createGameIdValidation()
        ];
    }

    private function createGameIdValidation()
    {
        return new Constraints\Required(
            [
                new PositiveInteger()
            ]
        );
    }

}
