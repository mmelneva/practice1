<?php namespace App\Services\Repositories\ProductHomePageAssociation;

use App\Models\ProductHomepageAssociation;

class EloquentHomePageAssociationRepo implements ProductHomePageAssociationRepoInterface
{
    const POSITION_STEP = 10;

    /**
     * @inheritdoc
     */
    public function updateAssociation($homePageId, $productId, array $associationData)
    {
        $assoc = $this->findOrNew($homePageId, $productId);
        $associationData = $this->prepareInputData($homePageId, $associationData, $assoc);
        $assoc->fill($associationData);
        $assoc->save();
    }

    public function deleteAssociation($homePageId, $productId)
    {
        $assoc = $this->findOrNew($homePageId, $productId);
        $assoc->delete();
    }

    /**
     * Prepare data before saving
     * @param $homePageId
     * @param array $data
     * @param $assocModel
     * @return array
     */
    private function prepareInputData($homePageId, array $data, $assocModel)
    {
        if (isset($data['position']) && trim($data['position']) == '' && $assocModel->exists ||
            (!isset($data['position']) || trim($data['position']) == '') && !$assocModel->exists) {
            $maxPosition = ProductHomepageAssociation::where('home_page_id', $homePageId)->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }

            $data['position'] = $maxPosition + self::POSITION_STEP;
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function findOrNew($homePageId, $productId)
    {
        $assoc = ProductHomepageAssociation::where('home_page_id', $homePageId)
            ->where('catalog_product_id', $productId)->first();

        if (is_null($assoc)) {
            $assoc = new ProductHomepageAssociation();
            $assoc->home_page_id = $homePageId;
            $assoc->catalog_product_id = $productId;
        }

        return $assoc;
    }


    /**
     * @inheritdoc
     */
    public function getAssociationsForPage($homePageId)
    {
        $groupedAssociations = [];
        $associations = ProductHomepageAssociation::where('home_page_id', $homePageId)->get();
        foreach ($associations as $assoc) {
            $groupedAssociations[$assoc->catalog_product_id] = $assoc;
        }

        return $groupedAssociations;
    }


    /**
     * @inheritdoc
     */
    public function getAssociationsForPageAndProducts($homePageId, array $productIds)
    {
        if (count($productIds) === 0) {
            $productIds = [null];
        }

        return ProductHomepageAssociation::where('home_page_id', $homePageId)
            ->whereIn('catalog_product_id', $productIds)
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function getAssociationForPageAndProduct($homePageId, $productId)
    {
        return ProductHomepageAssociation::where('home_page_id', $homePageId)
            ->where('catalog_product_id', $productId)
            ->first();
    }
}
