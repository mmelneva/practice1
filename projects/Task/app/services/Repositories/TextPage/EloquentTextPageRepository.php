<?php namespace App\Services\Repositories\TextPage;

use App\Models\Node;
use App\Models\TextPage;

class EloquentTextPageRepository implements TextPageRepositoryInterface
{
    public function findForNodeOrNew(Node $node)
    {
        $textPage = $node->textPage()->first();
        if (is_null($textPage)) {
            $textPage = new TextPage();
            $textPage->node()->associate($node);
        }

        return $textPage;
    }
}
