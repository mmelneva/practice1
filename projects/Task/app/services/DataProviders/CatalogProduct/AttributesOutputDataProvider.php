<?php namespace App\Services\DataProviders\CatalogProduct;

use App\Models\Attribute;
use App\Models\CatalogProduct;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttributesOutputDataProvider
{
    private $attributeRepository;
    private $attributeValueRepository;
    private $catalogProductRepository;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        CatalogProductRepositoryInterface $catalogProductRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->catalogProductRepository = $catalogProductRepository;
    }

    public function getFullForProduct(CatalogProduct $catalogProduct)
    {
        $productAttributes = $this->attributeRepository->forProductPage();
        $productValues = $this->attributeValueRepository->getValuesForProduct(
            $catalogProduct->id,
            $productAttributes
        );

        $productAttributesValues = [];
        foreach ($productValues as $value) {
            $productAttributesValues[$value->attribute_id] = $this->attributeValueRepository->getValueVariant($value);
        }

        $result = [];
        foreach ($productAttributes as $attribute) {
            if (!empty($productAttributesValues[$attribute->id])) {
                $result[] = [
                    'attribute' => $attribute,
                    'value' => $productAttributesValues[$attribute->id]
                ];
            }
        }

        return $result;
    }

    public function getSimilarProducts(CatalogProduct $product)
    {
        $similarProductsAttributes = $this->attributeRepository->forSimilarProducts();

        $productValues = $this->attributeValueRepository->getValuesForProduct(
            $product->id,
            $similarProductsAttributes
        );

        $productAttributesValues = [];
        foreach ($productValues as $value) {
            $productAttributesValues[$value->attribute_id] = $this->attributeValueRepository->getValueVariant($value);
        }

        $result = [];
        foreach ($similarProductsAttributes as $attribute) {
            if (empty($productAttributesValues[$attribute->id])) {
                continue;
            }

            $products = Collection::make([]);
            switch ($attribute->type) {
                case Attribute::TYPE_STRING:
                case Attribute::TYPE_NUMBER:
                    if (is_string($productAttributesValues[$attribute->id])) {
                        $value = $productAttributesValues[$attribute->id];
                        $products = $this->catalogProductRepository->getSimilarPublishedByStringAttribute($product, $attribute->id, $value);
                    }
                    break;
                case Attribute::TYPE_LIST:
                    if ($productAttributesValues[$attribute->id] instanceof \App\Models\AttributeAllowedValue) {
                        $value = $productAttributesValues[$attribute->id];
                        $products = $this->catalogProductRepository->getSimilarPublishedByListAttribute($product, $attribute->id, $value->id);
                    }
                    break;
                case Attribute::TYPE_MULTIPLE_VALUES:
                    if ($productAttributesValues[$attribute->id] instanceof \Illuminate\Database\Eloquent\Collection) {
                        $value = $productAttributesValues[$attribute->id];
                        $products = $this->catalogProductRepository->getSimilarPublishedByMultipleValuesAttribute($product, $attribute->id, $value->lists('id'));
                    }
                    break;
            }

            if ($products->count() > 0) {
                $result[] = [
                    'attribute' => $attribute,
                    'products' => $products,
                ];
            }
        }

        return $result;
    }
}
