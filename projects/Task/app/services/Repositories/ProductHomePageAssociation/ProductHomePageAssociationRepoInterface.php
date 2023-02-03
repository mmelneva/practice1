<?php namespace App\Services\Repositories\ProductHomePageAssociation;

interface ProductHomePageAssociationRepoInterface
{
    public function updateAssociation($homePageId, $productId, array $associationData);
    public function deleteAssociation($homePageId, $productId);

    public function findOrNew($homePageId, $productId);

    /**
     * Get list of associations for home page id.
     *
     * @param $homePageId
     * @return mixed
     */
    public function getAssociationsForPage($homePageId);

    /**
     * Get list of home page associations for page and product list.
     *
     * @param $homePageId
     * @param array $productIds
     * @return mixed
     */
    public function getAssociationsForPageAndProducts($homePageId, array $productIds);

    /**
     * Get  home page associations for page and product.
     *
     * @param $homePageId
     * @param $productId
     * @return mixed
     */
    public function getAssociationForPageAndProduct($homePageId, $productId);
}
