<?php namespace App\Services\Repositories\ProductTypePageAssociation;

interface ProdTypePageAssociationRepoInterface
{
    /**
     * Update data about product type page association.
     *
     * @param $productTypePageId
     * @param $productId
     * @param array $associationData
     * @return mixed
     */
    public function updateAssociation($productTypePageId, $productId, array $associationData);

    /**
     * Uncheck all the associations, which are belongs to page, but do not attached to next products.
     *
     * @param $productTypePageId
     * @param array $productIds
     * @return mixed
     */
    public function uncheckManualExceptProducts($productTypePageId, array $productIds = []);

    /**
     * Get existing association, or create new instance (without saving).
     *
     * @param $productTypePageId
     * @param $productId
     * @return mixed
     */
    public function findOrNew($productTypePageId, $productId);

    /**
     * Get list of associations for product type page id.
     *
     * @param $productTypePageId
     * @return mixed
     */
    public function getAssociationsForPage($productTypePageId);

    /**
     * Get list of product type page associations for page and product list.
     *
     * @param $prodTypePageId
     * @param array $productIds
     * @return mixed
     */
    public function getAssociationsForPageAndProducts($prodTypePageId, array $productIds);

    /**
     * Get  product type page associations for page and product.
     *
     * @param $productTypePageId
     * @param $productId
     * @return mixed
     */
    public function getAssociationForPageAndProduct($productTypePageId, $productId);
}
