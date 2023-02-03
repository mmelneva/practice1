<?php namespace App\Services\DataProviders\CatalogProduct;

use App\Models\CatalogProduct;

class ProductDataProvider
{

    private function getFieldsData(CatalogProduct $product, array $fields)
    {
        $result = [];
        foreach ($fields as $field) {
            if (!empty($product->{$field})) {
                $result[] =
                    [
                        'name' => trans("validation.attributes." . $field),
                        'value' => $product->{$field},
                    ];
            }
        }

        return $result;
    }

    private function getCheckboxData(CatalogProduct $product, array $fields)
    {
        $result = [];
        foreach ($fields as $field) {
            if (!empty($product->{$field})) {
                $result[] =
                    [
                        'name' => trans("validation.attributes." . $field),
                        'value' => 'Есть',
                    ];
            }
        }

        return $result;
    }

    /**
     * @param CatalogProduct $product
     * @param $relations
     * @return array
     */
    private function getOneToManyRelationsData(CatalogProduct $product, $relations)
    {
        $result = [];
        foreach ($relations as $relation) {
            if (!is_null($product->{$relation})) {
                if (!is_null($siteName = object_get($product->{$relation}, 'site_name')) &&
                    trim($siteName) != ''
                ) {
                    $value = $siteName;
                } else {
                    $value = object_get($product->{$relation}, 'name');
                }

                $result[] =
                    [
                        'name' => trans("validation.attributes." . $product->{$relation}()->getForeignKey()),
                        'value' => $value,
                    ];
            }
        }

        return $result;
    }

    /**
     * @param CatalogProduct $product
     * @param $relations - array of relations ['field' => 'relation']
     * @return array
     */
    private function getManyToManyRelationsData(CatalogProduct $product, $relations)
    {
        $result = [];

        foreach ($relations as $field => $relation) {
            if (count($relatedModels = $product->{$relation}) > 0) {
                $value = '';
                foreach ($relatedModels as $key => $model) {
                    $value .= $model->name . (isset($relatedModels[$key + 1]) ? ', ' : '');
                }
                $result[] =
                    [
                        'name' => trans("validation.attributes." . $field),
                        'value' => $value,
                    ];
            }
        }

        return $result;
    }
}
