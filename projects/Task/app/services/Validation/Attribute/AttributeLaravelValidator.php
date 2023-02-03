<?php namespace App\Services\Validation\Attribute;

use App\Services\Validation\AttributeAllowedValue\AttributeAllowedValueLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class AttributeLaravelValidator
 * @package  App\Services\Validation\Attribute
 */
class AttributeLaravelValidator extends AbstractLaravelValidator
{
    private $attributeAllowedValueLaravelValidator;
    private $attributeRepository;

    public function __construct(
        ValidatorFactory $validatorFactory,
        AttributeAllowedValueLaravelValidator $attributeAllowedValueLaravelValidator,
        AttributeRepositoryInterface $attributeRepository
    ) {

        parent::__construct($validatorFactory);

        $this->attributeAllowedValueLaravelValidator = $attributeAllowedValueLaravelValidator;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return [
            'name' => 'required',
            'type' => ['required', 'in:' . implode(',', array_flip($this->attributeRepository->getTypeVariants()))],
        ];
    }

    public function passes()
    {
        return parent::passes() && $this->passesAllowedValues();
    }

    public function passesAllowedValues()
    {
        $allowedValues = array_get($this->data, 'allowed_values', []);

        if (is_array($allowedValues)) {
            $allPasses = true;
            foreach ($allowedValues as $allowedValueKey => $allowedValueData) {
                $passes = $this->attributeAllowedValueLaravelValidator->with($allowedValueData)->passes();

                if (!$passes) {
                    $errors = $this->attributeAllowedValueLaravelValidator->errors();
                    foreach ($errors as $errorKey => $errorMessage) {
                        $this->errors["allowed_values.{$allowedValueKey}.{$errorKey}"] = $errorMessage;
                    }
                }

                $allPasses = $allPasses && $passes;
            }
        } else {
            $allPasses = false;
        }

        return $allPasses;
    }
}
