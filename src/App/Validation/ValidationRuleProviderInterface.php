<?php
namespace MyApp\Validation;

interface ValidationRuleProviderInterface
{
    /**
     * @return array
     */
    public function getRules(): array;
}
