<?php namespace App\Services\OrderButton;

use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;

class OrderButtonTypeGenerator {

    private $catalogCategoryRepository;
    private $productTypePageRepository;

    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        ProductTypePageRepositoryInterface $productTypePageRepository
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->productTypePageRepository = $productTypePageRepository;
    }

    public function setRandomParameter()
    {
        $pages = array_merge(
            $this->catalogCategoryRepository->all()->all(),
            $this->productTypePageRepository->all()->all()
        );

        foreach ($pages as $page) {
            if (rand(0, 100) / 100 < 0.5) {
                $type = 'specify';
            } else {
                $type = 'calculation';
            }
            $page->update(['type_order_button' => $type]);
        }
    }
}
