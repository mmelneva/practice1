<?php namespace App\Services\FormProcessors\HomePagesSubProcessor;

use App\Models\HomePage;
use App\Services\Repositories\ProductHomePageAssociation\ProductHomePageAssociationRepoInterface;

/**
 * @package  App\Services\FormProcessors\HomePagesSubProcessor
 */
class ProductsSubProcessor implements HomePageSubProcessorInterface
{
    private $productHomePageAssociationRepo;

    public function __construct(
        ProductHomePageAssociationRepoInterface $productHomePageAssociationRepo
    )
    {
        $this->productHomePageAssociationRepo = $productHomePageAssociationRepo;
    }

    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(HomePage $homePage, $formData)
    {
        $associatedProductsData = array_get($formData, 'associated_products', []);

        if (!is_array($associatedProductsData)) {
            return;
        }

        $preparedProductIds = [];
        foreach ($associatedProductsData as $productId => $associationData) {
            if (
                !is_array($associationData) ||
                array_get($associationData, 'manual') == 0 && count($associationData) == 1
            ) {
                continue;
            }

            $this->productHomePageAssociationRepo->updateAssociation(
                $homePage->id,
                $productId,
                $associationData
            );

            $preparedProductIds[] = $productId;
        }

        $existingAssociation = $homePage->homePageAssociations()->lists('catalog_product_id');
        $notInList = array_diff($existingAssociation, $preparedProductIds);

        if (count($notInList)) {
            foreach ($notInList as $id) {
                $this->productHomePageAssociationRepo->deleteAssociation($homePage->id, $id);
            }
        }

        return true;
    }
}
