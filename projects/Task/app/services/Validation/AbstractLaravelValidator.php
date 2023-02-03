<?php namespace App\Services\Validation;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

/**
 * Class AbstractLaravelValidator
 * Abstract validator, which uses Laravel validation
 * @package Validation
 */
abstract class AbstractLaravelValidator implements ValidableInterface
{
    /**
     * Validator.
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validatorFactory;

    /**
     * Validation data key => value array.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Validation errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Id of current object.
     *
     * @var int|null
     */
    protected $currentId = null;

    /**
     * Create validator
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function getRules()
    {
        return [];
    }

    /**
     * Custom validation error messages.
     *
     * @return array
     */
    protected function getMessages()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function passes()
    {
        $validator = $this->validatorFactory->make(
            $this->data,
            $this->getRules(),
            $this->getMessages()
        );
        $this->configValidator($validator);

        if ($validator->fails()) {
            $this->errors = $validator->messages()->toArray();

            return false;
        }

        return true;
    }

    /**
     * Config existing validator.
     *
     * @param Validator $validator
     */
    protected function configValidator(Validator $validator)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentId($id)
    {
        $this->currentId = $id;
        
        return $this;
    }
}
