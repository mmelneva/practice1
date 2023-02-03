<?php namespace App\Services\RepositoryFeatures\Tree;

/**
 * Interface PublishedTreeBuilderInterface
 * Tree builder interface for published tree.
 *
 * @package App\Services\RepositoryFeatures\Tree
 */
interface PublishedTreeBuilderInterface extends TreeBuilderInterface
{
    /**
     * List of published ids.
     *
     * @param \Eloquent $modelTemplate
     * @param null $rootId
     * @return array
     */
    public function getPublishedIds(\Eloquent $modelTemplate, $rootId = null);

    /**
     * Modify query to select published elements in lvl.
     *
     * @param $query
     * @return mixed
     */
    public function scopePublishedInLvl($query);

    /**
     * Modify query to select published elements according to tree.
     *
     * @param \Eloquent $modelTemplate
     * @param $query
     * @return mixed
     */
    public function scopePublishedInTree(\Eloquent $modelTemplate, $query);

    /**
     * Get published children for current element.
     *
     * @param \Eloquent $modelTemplate
     * @param $id
     * @return mixed
     */
    public function getPublishedChildren(\Eloquent $modelTemplate, $id);
}
