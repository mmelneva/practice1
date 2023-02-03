<?php namespace App\Services\Repositories\Attribute;

use App\Models\Attribute;
use App\Models\CatalogProduct;
use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\ListRepositoryInterface;
use App\Services\Repositories\SortableListRepositoryInterface;

/**
 * Interface CatalogProductAttributeRepositoryInterface
 * @package App\Services\Repositories\CatalogProductAttribute
 */
interface AttributeRepositoryInterface extends ListRepositoryInterface, CreateUpdateRepositoryInterface, SortableListRepositoryInterface
{
    public function getTypeVariants();

    public function findByIdOrNew($id, $columns = array('*'));

    public function allIn(array $attributeIds = []);

    public function getDisabledAttributesFor(CatalogProduct $product);

    public function getAllowedValuesVariants(Attribute $attribute, $nullVariant = false);

    public function getAllowedValues(Attribute $attribute);

    public function forProductPage();

    public function allWithNamedKeys();

    /**
     * Fill attribute with data and save
     *
     * @param Attribute $attribute
     * @param array $data
     * @return mixed
     */
    public function fillAndSave(Attribute $attribute, array $data);

    /**
     * Get attributes for similar products block
     * @return mixed
     */
    public function forSimilarProducts();
}
