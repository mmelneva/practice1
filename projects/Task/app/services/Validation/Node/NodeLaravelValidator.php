<?php namespace App\Services\Validation\Node;

use App\Services\StructureTypes\TypeContainer;
use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

class NodeLaravelValidator extends AbstractLaravelValidator
{
    /**
     * @var TypeContainer
     */
    private $typeContainer;

    public function __construct(ValidatorFactory $validatorFactory, TypeContainer $typeContainer)
    {
        parent::__construct($validatorFactory);
        $this->typeContainer = $typeContainer;
    }

    /**
     * {@inheritDoc}
     */
    protected function getRules()
    {
        $rules = [];
        $rules['name'] = "required";
        $rules['alias'] = "unique:nodes,alias,{$this->currentId}";
        $rules['parent_id'] = "exists:nodes,id";

        $typeList = $this->typeContainer->getEnabledTypeList($this->currentId);
        $rules['type'] = ["required", "in:" . implode(',', array_keys($typeList))];

        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    protected function configValidator(Validator $validator)
    {
        // alias should be required if it's unique page type
        $notUniqueTypeIdList = [];
        $availableTypes = $this->typeContainer->getTypeList();
        foreach ($availableTypes as $typeKey => $type) {
            if (!$type->getUnique()) {
                $notUniqueTypeIdList[] = $typeKey;
            }
        }

        $validator->sometimes(
            'alias',
            'required',
            function ($input) use ($notUniqueTypeIdList) {
                return in_array($input->type, $notUniqueTypeIdList);
            }
        );
    }
}
