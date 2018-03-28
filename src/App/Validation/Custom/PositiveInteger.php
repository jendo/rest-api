<?php
namespace MyApp\Validation\Custom;

use Symfony\Component\Validator\Constraint;

class PositiveInteger extends Constraint
{
    public $message = 'This value should be of type int and bigger than 0.';
}
