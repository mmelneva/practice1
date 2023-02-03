<?php namespace App\Services\Catalog\Filter\Lens;

use App\Services\Catalog\Filter\LensFeatures\ArrayLens;
use App\Services\Repositories\ListRepositoryInterface;

/**
 * Class BelongsToAttributeLens
 * Filter by parameter which is associated with products in way, when each product can have selected one of this
 * parameters. So, products "belongs to" attributes.
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class BelongsToAttributeLens implements LensInterface
{
    use ArrayLens;

    /**
     * @var string
     */
    private $foreignKey;
    /**
     * @var ListRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param string $foreignKey
     * @param ListRepositoryInterface $attributeRepository
     */
    public function __construct($foreignKey, ListRepositoryInterface $attributeRepository)
    {
        $this->foreignKey = $foreignKey;
        $this->attributeRepository = $attributeRepository;
    }

    public function modifyQuery($query, $lensData)
    {
        if (!is_array($lensData)) {
            $lensData = [];
        }

        if (count($lensData) > 0) {
            $query->whereIn($this->foreignKey, $lensData);
        }
    }

    public function getVariants($query, $lensData)
    {
        if (!is_array($lensData)) {
            $lensData = [];
        }

        $ids = $query->select($this->foreignKey)->distinct()->lists($this->foreignKey);
        if (count($ids) == 0) {
            $ids = [null];
        }
        $modelList = $this->attributeRepository->allByIds($ids);

        $variants = [];
        foreach ($modelList as $model) {
            $variants[] = [
                'value' => $model->id,
                'title' => $model->name,
                'checked' => in_array($model->id, $lensData),
            ];
        }

        if (count($variants) === 0) {
            $variants = null;
        }

        return $variants;
    }
}
