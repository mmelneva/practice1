<?php namespace App\Services\Repositories\Attribute;

use App\Models\Attribute;
use App\Models\CatalogProduct;
use App\Services\Repositories\Generic\SortableEloquentNamedModelRepository;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentCatalogProductAttributeRepository
 * @package  App\Services\Repositories\CatalogProductAttribute
 */
class EloquentAttributeRepository extends SortableEloquentNamedModelRepository implements AttributeRepositoryInterface
{
    public function __construct(EloquentAttributeToggler $attributeToggler, PossibleVariants $possibleVariants)
    {
        parent::__construct(new Attribute, $attributeToggler, $possibleVariants);
    }

    public function getTypeVariants()
    {
        return [
            Attribute::TYPE_STRING => 'Строка',
            Attribute::TYPE_NUMBER => 'Число',
            Attribute::TYPE_LIST => 'Список',
            Attribute::TYPE_MULTIPLE_VALUES => 'Множественные значения',
        ];
    }

    public function findByIdOrNew($id, $columns = array('*'))
    {
        return $this->modelInstance->findOrNew($id, $columns);
    }

    public function getDisabledAttributesFor(CatalogProduct $product)
    {
        $selectedAttributeIds = $product->attributeValues->lists('attribute_id');

        $query = $this->modelInstance->query();
        if (count($selectedAttributeIds) > 0) {
            $query->whereNotIn('id', $selectedAttributeIds);
        }

        $this->scopeOrdered($query);

        return $query->get();
    }

    public function allIn(array $attributeIds = [])
    {
        $query = $this->modelInstance->query();
        $this->scopeOrdered($query);

        if (count($attributeIds) > 0) {
            $attributeIds[] = null;
        }

        return $query->whereIn('id', $attributeIds)->get();
    }

    public function getAllowedValuesVariants(Attribute $attribute, $nullVariant = false)
    {
        $query = $attribute->allowedValues();
        $this->scopeOrdered($query);

        $variants = [];
        if ($nullVariant) {
            $variants[0] = trans('validation.attributes.not_chosen');
        }

        foreach ($query->lists('value', 'id') as $id => $value) {
            $variants[$id] = $value;
        };

        return $variants;
    }

    public function getAllowedValues(Attribute $attribute)
    {
        $query = $attribute->allowedValues();
        $this->scopeOrdered($query);

        return $query->get();
    }

    public function forProductPage()
    {
        $query = $this->modelInstance->query();
        $query->where('on_product_page', true);
        $this->scopeOrdered($query);

        return $query->get();
    }

    public function allWithNamedKeys()
    {
        $attributes = Collection::make([]);
        foreach ($this->all() as $a) {
            $attributes[$a->name] = $a;
        }

        return $attributes;
    }

    private function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function fillAndSave(Attribute $attribute, array $data)
    {
        $attribute->fill($data);
        $attribute->save();
    }

    public function forSimilarProducts()
    {
        $query = $this->modelInstance->query();
        $query->where('use_in_similar_products', true);
        $this->scopeOrdered($query);

        return $query->get();
    }
}
