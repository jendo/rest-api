<?php
namespace MyApp\Api\GameResult\Get;

use MyApp\Api\GameResult\QueryParams;
use MyApp\Error\ErrorDTO;
use MyApp\Middleware\ValidatorMiddleware;
use MyApp\Validation\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetValidatorMiddleware extends ValidatorMiddleware
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var GetValidationRuleProvider
     */
    private $ruleProvider;

    /**
     * @param Validator $validator
     * @param GetValidationRuleProvider $ruleProvider
     */
    public function __construct(Validator $validator, GetValidationRuleProvider $ruleProvider)
    {
        $this->validator = $validator;
        $this->ruleProvider = $ruleProvider;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ErrorDTO[]
     */
    protected function getErrors(ServerRequestInterface $request, ResponseInterface $response): array
    {
        $route = $request->getAttribute('route');

        $inputToValidate[QueryParams::GAME_ID] = (int) $route->getArgument(QueryParams::GAME_ID);

        return $this->validator->validate($inputToValidate, $this->ruleProvider->getRules());
    }
}
