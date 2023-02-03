<?php namespace App\Commands;

use App\Models\CatalogProduct;
use Illuminate\Console\Command;

class SetMaterialAttribute extends Command
{
    protected $name = 'app:set-material-attribute';
    protected $description = 'Set material attribute for products.';

    const MATERIAL_ATTRIBUTE_ID = 9;

    const MASSIVE_VALUE_ID_OF_MATERIAL_ATTRIBUTE = 28;

    private $categoryIds = [3, 4, 5];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $attributeRepository = \App::make('App\Services\Repositories\Attribute\AttributeRepositoryInterface');
        $attributeValueRepository = \App::make(
            'App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface'
        );

        $attribute = $attributeRepository->findById(self::MATERIAL_ATTRIBUTE_ID);
        if (!$attribute->is_multiple_values) {
            echo 'Тип параметра Материал должен быть "Множественные значения"';

            return;
        }
        $allAllowedValueIdList = $attribute->allowedValues->lists('id');
        $allowedValueIdList = array_diff($allAllowedValueIdList, [self::MASSIVE_VALUE_ID_OF_MATERIAL_ATTRIBUTE]);
        if (count($allowedValueIdList) == 0) {
            return;
        }
        $attributeValueData = ['allowed_value_id_list' => $allowedValueIdList];

        $productIdsWithMassiveValue = $this->getProductIdsWithMassiveValueOfMaterialAttribute();
        $products = $this->getProductWithoutMassiveValueOfMaterialAttribute($productIdsWithMassiveValue);

        foreach ($products as $product) {
            $attributeValueRepository->createOrUpdate(
                self::MATERIAL_ATTRIBUTE_ID,
                $product->id,
                $attributeValueData
            );
        }
    }

    private function getProductIdsWithMassiveValueOfMaterialAttribute()
    {
        return CatalogProduct::join(
            'attribute_values',
            'attribute_values.product_id',
            '=',
            'catalog_products.id'
        )->join(
            'attribute_multiple_values',
            'attribute_multiple_values.attribute_value_id',
            '=',
            'attribute_values.id'
        )
            ->where('attribute_multiple_values.allowed_value_id', self::MASSIVE_VALUE_ID_OF_MATERIAL_ATTRIBUTE)
            ->where('attribute_values.attribute_id', self::MATERIAL_ATTRIBUTE_ID)
            ->select('catalog_products.*')
            ->distinct()
            ->lists('id');
    }

    private function getProductWithoutMassiveValueOfMaterialAttribute(array $productIdsWithMassiveValue)
    {
        if (count($productIdsWithMassiveValue) == 0) {
            $productIdsWithMassiveValue[] = -1;
        }

        return $query = CatalogProduct::join(
            'product_category_associations',
            'product_category_associations.product_id',
            '=',
            'catalog_products.id'
        )
            ->whereIn('product_category_associations.category_id', $this->categoryIds)
            ->whereNotIn('catalog_products.id', $productIdsWithMassiveValue)
            ->select('catalog_products.*')
            ->distinct()
            ->get();
    }
}
