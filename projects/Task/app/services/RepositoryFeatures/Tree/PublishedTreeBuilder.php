<?php namespace App\Services\RepositoryFeatures\Tree;

/**
 * Class PublishedTreeBuilder
 * @package App\Services\RepositoryFeatures\Tree
 */
class PublishedTreeBuilder extends TreeBuilder implements PublishedTreeBuilderInterface
{

    /**
     * @inheritDoc
     */
    public function getPublishedIds(\Eloquent $modelTemplate, $rootId = null)
    {
        $idsList = [];

        $queue = [];
        $addToQueue = function ($list) use (&$queue) {
            foreach ($list as $c) {
                array_push($queue, $c);
            }
        };

        $query = $modelTemplate->query();
        if (is_null($rootId)) {
            $this->scopeRooted($query);
            $this->scopePublishedInLvl($query);
        } else {
            $query->where('id', $rootId);
            $this->scopePublishedInLvl($query);
        }

        $rooted = $query->get();
        $addToQueue($rooted);

        while (!is_null($c = array_pop($queue))) {
            $idsList[] = $c->id;

            $childrenQuery = $c->children();
            $this->scopePublishedInLvl($childrenQuery);
            $addToQueue($childrenQuery->get());
        }

        return $idsList;
    }

    /**
     * @inheritDoc
     */
    public function scopePublishedInTree(\Eloquent $modelTemplate, $query)
    {
        $ids = $this->getPublishedIds($modelTemplate);
        if (count($ids) == 0) {
            $ids = [null];
        }

        $query->whereIn('id', $ids);
    }

    /**
     * @inheritDoc
     */
    public function getPublishedChildren(\Eloquent $modelTemplate, $id)
    {
        $query = $modelTemplate->where('parent_id', $id);
        $this->orderScopes->scopeOrdered($query);
        $this->scopePublishedInLvl($query);

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function scopePublishedInLvl($query)
    {
        return $query->where('publish', true);
    }
}
