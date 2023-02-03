<?php namespace App\Services\Repositories\Node;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;

interface NodeRepositoryInterface extends CreateUpdateRepositoryInterface, ToggleableRepositoryInterface
{
    /**
     * Make new node instance.
     *
     * @param array $data
     * @return \App\Models\Node
     */
    public function newInstance(array $data = []);

    /**
     * Find node by id.
     *
     * @param $id
     * @param bool|false $published
     * @return \App\Models\Node|null
     */
    public function findById($id, $published = false);

    /**
     * Delete node.
     *
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * Get path in tree for the node id.
     *
     * @param $id
     * @return mixed
     */
    public function getTreePath($id);

    /**
     * Get sorted node hierarchical tree.
     *
     * @return mixed
     */
    public function getTree();

    /**
     * Get collapsed tree.
     *
     * @param $id
     * @return mixed
     */
    public function getCollapsedTree($id = null);

    /**
     * Get children
     *
     * @param $nodeId
     * @return mixed
     */
    public function getPublishedChildren($nodeId);

    /**
     * Get node for page by alias.
     *
     * @param $alias
     * @param $published
     * @return mixed
     */
    public function findByAlias($alias, $published = true);

    /**
     * Get node for page by page type.
     *
     * @param $type
     * @param $published
     * @return mixed
     */
    public function findByType($type, $published = false);

    /**
     * Get all nodes with current type;
     *
     * @param $type
     * @return mixed
     */
    public function allByType($type);

    /**
     * Update positions.
     *
     * @param array $positions
     * @return mixed
     */
    public function updatePositions(array $positions);

    /**
     * Get variants to choose parent.
     *
     * @param null $id
     * @param null $root
     * @return mixed
     */
    public function getParentVariants($id = null, $root = null);

    /**
     * @param $nodeId
     * @return mixed
     */
    public function getPublishedChildrenContentModels($nodeId);

    /**
     * Get published node for dynamic page by url.
     *
     * @param array $url
     * @return mixed
     */
    public function findByUrl(array $url);

    /** Get relative path for dynamic page url
     * @param $nodeId
     * @return mixed
     */
    public function getDynamicPageRelativePath($nodeId);

    /**
     * Get tree of existent nodes for site map
     *
     * @param bool $mapPage
     * @return mixed
     */
    public function getSiteMapPageTree($mapPage = false);

    /** Get tree for site map page
     * @return mixed
     */
    public function getMapPageTree();

    /**
     * Get cached all published nodes ids
     * @return mixed
     */
    public function getAllPublishedIds();

    /**
     * Get published ordered nodes with top_menu = true
     * @return mixed
     */
    public function getTopMenuElements();

    /**
     * Get published ordered nodes with scrolled_menu_top = true
     * @return mixed
     */
    public function getScrolledTopMenuElements();


    /**
     * Get published ordered nodes with menu_bottom = true
     * @return mixed
     */
    public function getBottomMenuElements();
}
