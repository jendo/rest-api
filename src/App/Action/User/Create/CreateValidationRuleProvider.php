<?php
namespace MyApp\Action\User\Create;

use MyApp\Action\User\UserApiFields;
use MyApp\Validation\ValidationRuleProviderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateValidationRuleProvider implements ValidationRuleProviderInterface
{
    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            UserApiFields::USER_ID => new NotBlank(),
            UserApiFields::NAME => new NotBlank(),
            UserApiFields::SURNAME => new NotBlank(),
        ];
    }
}
