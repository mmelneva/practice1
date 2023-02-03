<?php namespace App\Services\Repositories\ProductTypePage;

use App\Models\CatalogCategory;
use App\Services\Repositories\Node\NodeContentRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;

interface ProductTypePageRepositoryInterface extends NodeContentRepositoryInterface, ToggleableRepositoryInterface
{
    /** All productTypePages
     *
     * @return mixed
     */
    public function all();


    /** Check/add association - node with productTypePage
     * @return mixed
     */
    public function associateProductTypePageToNode();


    /**
     * All published.
     *
     * @return mixed
     */
    public function allPublishedWithFilter();

    /**
     * Find one published by id.
     *
     * @param $id
     * @return \App\Models\ProductTypePage|null
     */
    public function findPublished($id);

    /**
     * Get productTypePages for popular block on home page
     *
     * @return mixed
     */
    public function getPopularOnHomePage();

    /**
     * Get tree of productTypePages .
     *
     * @return mixed
     */
    public function getTree();

    /**
     * Find category by id.
     *
     * @param $id
     * @return \App\Models\ProductTypePage|null
     */
    public function findById($id);

    /**
     * Toggle an attribute.
     *
     * @param $id
     * @param $attribute
     * @return \Eloquent|null
     */
    public function toggleAttribute($id, $attribute);

}
