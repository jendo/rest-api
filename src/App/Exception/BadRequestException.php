<?php
namespace MyApp\Exception;

use Exception;

class BadRequestException extends Exception
{
    /**
     * @var string[]
     */
    private $errors;

    /**
     * @param string|string[] $errors
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($errors, $code = 0, Exception $previous = null)
    {
        $this->errors = is_array($errors) ? $errors : [$errors];

        parent::__construct(implode(', ', $this->errors), $code, $previous);
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        $errors = [];
        foreach ($this->errors as $error) {
            $errors[]['message'] = $error;
        }

        return $errors;
    }

}
