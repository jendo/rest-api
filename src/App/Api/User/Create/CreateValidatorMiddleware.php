<?php
namespace MyApp\Api\User\Create;

use MyApp\Error\ErrorDTO;
use MyApp\Middleware\ValidatorMiddleware;
use MyApp\Validation\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateValidatorMiddleware extends ValidatorMiddleware
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var CreateValidationRuleProvider
     */
    private $ruleProvider;

    /**
     * @param Validator $validator
     * @param CreateValidationRuleProvider $ruleProvider
     */
    public function __construct(Validator $validator, CreateValidationRuleProvider $ruleProvider)
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
        $input = $request->getParsedBody();

        if ($input === null) {
            return [new ErrorDTO('', 'Body must not be empty')];
        }

        return $this->validator->validate($input, $this->ruleProvider->getRules());
    }
}
