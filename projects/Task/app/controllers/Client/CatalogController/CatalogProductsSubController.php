<?php namespace App\Controllers\Client\CatalogController;

use App\Models\CatalogProduct;
use App\Services\DataProviders\CatalogProduct\AttributesOutputDataProvider;
use App\Services\DataProviders\CatalogProduct\ProductDataProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface;

class CatalogProductsSubController extends CatalogSubController
{
    private $attributesOutputDataProvider;
    private $catalogProductRepository;
    private $productGalleryImageRepository;
    private $productDataProvider;
    /**
     * @param AttributesOutputDataProvider $attributesOutputDataProvider
     * @param ProductDataProvider $productDataProvider
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param ProductGalleryImageRepositoryInterface $productGalleryImageRepository
     * @param NodeRepositoryInterface $nodeRepository
     */
    public function __construct(
        AttributesOutputDataProvider $attributesOutputDataProvider,
        ProductDataProvider $productDataProvider,
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        ProductGalleryImageRepositoryInterface $productGalleryImageRepository,
        NodeRepositoryInterface $nodeRepository
    ) {
        parent::__construct($nodeRepository, $catalogCategoryRepository);
        $this->attributesOutputDataProvider = $attributesOutputDataProvider;
        $this->productDataProvider = $productDataProvider;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->productGalleryImageRepository = $productGalleryImageRepository;
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function getCatalogProductResponse(CatalogProduct $product)
    {
        $rootCategory = \CatalogPathFinder::getProductRoot($product);
        $metaData = \MetaHelper::metaForObject($product);
        $productAttributes = $this->attributesOutputDataProvider->getFullForProduct($product);
        $isPublishedCategory = $this->catalogCategoryRepository->checkPublishInTreeById($product->category_id);
        $similarProducts = $this->attributesOutputDataProvider->getSimilarProducts($product);

        return \View::make('client.catalog_products.product')
            ->with(compact('product', 'rootCategory', 'productAttributes', 'similarProducts'))
            ->with('productImages', $this->getProductImages($product))
            ->with('breadcrumbs', $this->getBreadcrumbs($product, $isPublishedCategory))
            ->with('page_type', 'productpage')
            ->with($metaData);
    }

    private function getProductImages(CatalogProduct $product)
    {
        $productImages = [];

        $getNameWithPrefix = function ($name) use ($product) {
            return "{$product->name}. $name";
        };

        if (with($imgAttachment = $product->getAttachment('image'))->exists()) {
            $productImages[] = [
                'name_with_prefix' => $getNameWithPrefix(''),
                'attachment' => $imgAttachment,
            ];
        }

        $productGalleryImageList = $this->productGalleryImageRepository->allForProduct($product, true);
        foreach ($productGalleryImageList as $galleryImage) {
            if (with($galleryImgAttachment = $galleryImage->getAttachment('image'))->exists()) {
                $productImages[] = [
                    'name_with_prefix' => $getNameWithPrefix($galleryImage->name),
                    'attachment' => $galleryImgAttachment,
                ];
            }
        }

        return $productImages;
    }
}
