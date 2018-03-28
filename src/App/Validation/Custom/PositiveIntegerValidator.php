<?php
namespace MyApp\Validation\Custom;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositiveIntegerValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_int($value) || $value < 1) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
