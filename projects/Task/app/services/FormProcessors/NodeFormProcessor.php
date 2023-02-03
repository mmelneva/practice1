<?php namespace App\Services\FormProcessors;

use App\Services\FormProcessors\Features\AutoAlias;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\StructureTypes\TypeContainer;
use App\Services\Validation\ValidableInterface;

class NodeFormProcessor extends CreateUpdateFormProcessor
{
    use AutoAlias;

    private $typeContainer;

    public function __construct(
        ValidableInterface $validator,
        NodeRepositoryInterface $repository,
        TypeContainer $typeContainer
    ) {
        parent::__construct($validator, $repository);
        $this->typeContainer = $typeContainer;
    }


    protected function prepareInputData(array $data)
    {
        $data = $this->setAutoAlias($data);

        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        if (isset($data['type'])) {
            $typeObject = $this->typeContainer->getTypeList()[$data['type']];
            if ($typeObject->getUnique()) {
                $data['alias'] = null;
            }
        }

        return $data;
    }
}
