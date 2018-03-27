<?php
namespace MyApp\Action\User\Create;

use MyApp\Action\User\UserApiFields;
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
            UserApiFields::USER_ID => $this->createUserIdValidation(),
            UserApiFields::NAME => $this->createUserNameValidation(),
            UserApiFields::SURNAME => $this->createSurnameValidation(),
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
                        $userData = $this->repository->getUserData($userId);
                        if ($userData === []) {
                            $context->addViolation(
                                sprintf('User with id %d does not exist.', $userId)
                            );
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
