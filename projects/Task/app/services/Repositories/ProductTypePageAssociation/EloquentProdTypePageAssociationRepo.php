<?php namespace App\Services\Repositories\ProductTypePageAssociation;

use App\Models\ProductTypePageAssociation;

class EloquentProdTypePageAssociationRepo implements ProdTypePageAssociationRepoInterface
{
    const POSITION_STEP = 10;

    /**
     * @inheritdoc
     */
    public function updateAssociation($productTypePageId, $productId, array $associationData)
    {
        $assoc = $this->findOrNew($productTypePageId, $productId);
        $associationData = $this->prepareInputData($productTypePageId, $associationData, $assoc);
        $assoc->fill($associationData);
        $assoc->save();
    }

    /**
     * Prepare data before saving
     * @param $productTypePageId
     * @param array $data
     * @param $assocModel
     * @return array
     */
    private function prepareInputData($productTypePageId, array $data, $assocModel)
    {
        if (isset($data['position']) && trim($data['position']) == '' && $assocModel->exists ||
            (!isset($data['position']) || trim($data['position']) == '') && !$assocModel->exists) {
            $maxPosition = ProductTypePageAssociation::where('product_type_page_id', $productTypePageId)->max('position');
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
    public function uncheckManualExceptProducts($productTypePageId, array $productIds = [])
    {
        $query = ProductTypePageAssociation::where('product_type_page_id', $productTypePageId);
        if (count($productIds) > 0) {
            $query->whereNotIn('catalog_product_id', $productIds);
        }
        $assocList = $query->get();

        foreach ($assocList as $assoc) {
            $assoc->manual = false;
            $assoc->save();
        }
    }


    /**
     * @inheritdoc
     */
    public function findOrNew($productTypePageId, $productId)
    {
        $assoc = ProductTypePageAssociation::where('product_type_page_id', $productTypePageId)
            ->where('catalog_product_id', $productId)->first();

        if (is_null($assoc)) {
            $assoc = new ProductTypePageAssociation();
            $assoc->product_type_page_id = $productTypePageId;
            $assoc->catalog_product_id = $productId;
        }

        return $assoc;
    }


    /**
     * @inheritdoc
     */
    public function getAssociationsForPage($productTypePageId)
    {
        $groupedAssociations = [];
        $associations = ProductTypePageAssociation::where('product_type_page_id', $productTypePageId)->get();
        foreach ($associations as $assoc) {
            $groupedAssociations[$assoc->catalog_product_id] = $assoc;
        }

        return $groupedAssociations;
    }


    /**
     * @inheritdoc
     */
    public function getAssociationsForPageAndProducts($prodTypePageId, array $productIds)
    {
        if (count($productIds) === 0) {
            $productIds = [null];
        }

        return ProductTypePageAssociation::where('product_type_page_id', $prodTypePageId)
            ->whereIn('catalog_product_id', $productIds)
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function getAssociationForPageAndProduct($productTypePageId, $productId)
    {
        return ProductTypePageAssociation::where('product_type_page_id', $productTypePageId)
            ->where('catalog_product_id', $productId)
            ->first();
    }
}
