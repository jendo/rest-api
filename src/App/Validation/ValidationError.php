<?php
namespace MyApp\Validation;

class ValidationError
{
    const INVALID_VALUE_TYPE = 'invalidValue';
    const FIELD_NOT_EXPECTED = 'fieldNotExpected';
    const FIELD_MISSING = 'fieldMissing';
    const FIELD_CUSTOM_FIELDS_ERROR = 'customFieldsError';

    /**
     * @var string
     */
    private $propertyPath;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $code;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param string $propertyPath
     * @param string $message
     * @param string $code
     * @param array $parameters
     */
    public function __construct($propertyPath, $message, $code = '', array $parameters = [])
    {
        $this->propertyPath = $propertyPath;
        $this->message = $message;
        $this->code = $code;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%s] %s', $this->propertyPath, $this->message);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}
