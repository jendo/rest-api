<?php
namespace MyApp\Validation;

use MyApp\Error\ErrorDTO;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validation;

class Validator
{

    /**
     * @param array $inputData
     * @param array $rules
     * @return ErrorDTO[]
     */
    public function validate(array $inputData, array $rules): array
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($inputData, new Collection($rules));

        $errors = [];
        /** @var  ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errors[] = new ErrorDTO($violation->getPropertyPath(), $violation->getMessage());
        }

        return $errors;
    }
}
