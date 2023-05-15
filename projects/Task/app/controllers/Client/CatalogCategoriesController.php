<?php namespace App\Controllers\Client;

//use App\Controllers\Admin\Features\ToggleFlags;
use App\Controllers\BaseController;
use App\Services\FormProcessors\CatalogCategoryFormProcessor;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;

class CatalogCategoriesController extends BaseController
{
// use ToggleFlags;

/**
* @var CatalogCategoryRepositoryInterface
*/
private $catalogCategoryRepository;

/**
* @var CatalogProductRepositoryInterface
*/
private $catalogProductRepository;

private $formProcessor;
private $productTypePageRepository;
/**
* @param CatalogProductRepositoryInterface $catalogProductRepository
*/

public function __construct(
CatalogCategoryRepositoryInterface $catalogCategoryRepository,
CatalogProductRepositoryInterface $catalogProductRepository,
CatalogCategoryFormProcessor $formProcessor,
ProductTypePageRepositoryInterface $productTypePageRepository
) {
$this->catalogCategoryRepository = $catalogCategoryRepository;
$this->catalogProductRepository = $catalogProductRepository;
$this->formProcessor = $formProcessor;
$this->productTypePageRepository = $productTypePageRepository;
}


public function getIndex()
{
//получение всех категорий из базы
$categoryTree = $this->catalogCategoryRepository->getTree();

//блок 1 получение все Id категорий в массив categoryIds
$categoryIds = array();
foreach ($categoryTree as $category) {
$categoryId = object_get($category, 'id');
array_push($categoryIds, $categoryId);
}
//end блок 1

//блок получения объектов продуктов в массив products
$products = array();
//проходим по списку categoryIds с целью получения списка продуктов по категории
foreach($categoryIds as $categoryId){
$productsData = $this->catalogProductRepository->allInCategory($categoryId);
foreach($productsData as $elm)
array_push($products, $elm);
}
return \View::make('client.catalog_products.index')
->with('categoryTree', $categoryTree)
->with('products', $products)
;
}
}