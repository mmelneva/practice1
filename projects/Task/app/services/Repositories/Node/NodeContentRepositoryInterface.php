<?php namespace App\Services\Repositories\Node;

use App\Models\Node;

interface NodeContentRepositoryInterface
{
    public function findForNodeOrNew(Node $node);
}
