<?php

namespace App\Validator;

abstract class AbstractValidator
{

    /**
     * @var bool
     */
    protected $is_valid;

    /** @var array */
    protected $errors = [];

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->is_valid === null) {
            $this->is_valid = $this->validate();
        }

        return $this->is_valid;
    }

    public abstract function getErrors();

    protected abstract function validate();

}