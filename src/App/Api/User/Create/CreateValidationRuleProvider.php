<?php
namespace MyApp\Api\User\Create;

use MyApp\Api\User\UserFields;
use MyApp\Exception\UserNotFoundException;
use MyApp\Redis\Repository;
use MyApp\Validation\ValidationRuleProviderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateValidationRuleProvider implements ValidationRuleProviderInterface
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
     * @return array
     */
    public function getRules(): array
    {
        return [
            UserFields::USER_ID => $this->createUserIdValidation(),
            UserFields::NAME => $this->createUserNameValidation(),
            UserFields::SURNAME => $this->createSurnameValidation(),
        ];
    }

    /**
     * @return Constraints\Required
     */
    private function createUserIdValidation(): Constraints\Required
    {
        return new Constraints\Required(
            [
                new Constraints\NotBlank(),
                new Constraints\Type('integer'),
                new Callback(
                    function ($userId, ExecutionContextInterface $context) {
                        $userAlreadyExists = true;

                        try {
                            $this->repository->getUserData($userId);
                        } catch (UserNotFoundException $e) {
                            $userAlreadyExists = false;
                        }

                        if ($userAlreadyExists) {
                            $context->addViolation(sprintf('User with id %d already exist.', $userId));
                        }
                    }
                )
            ]
        );
    }

    /**
     * @return Constraints\Required
     */
    private function createUserNameValidation(): Constraints\Required
    {
        return new Constraints\Required(
            [
                new Constraints\NotBlank()
            ]
        );
    }

    /**
     * @return Constraints\Required
     */
    private function createSurnameValidation(): Constraints\Required
    {
        return new Constraints\Required(
            [
                new Constraints\NotBlank()
            ]
        );
    }
}
