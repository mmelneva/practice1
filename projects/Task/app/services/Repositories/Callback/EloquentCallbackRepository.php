<?php namespace App\Services\Repositories\Callback;

use App\Models\Callback;
use App\Models\CallbackStatusConstants;
use App\Models\CallbackTypeConstants;
use App\Services\Repositories\Generic\EloquentNamedModelRepository;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;

/**
 * Class EloquentCallbackRepository
 * @package  App\Services\Repositories\Callback
 */
class EloquentCallbackRepository extends EloquentNamedModelRepository implements CallbackRepositoryInterface
{
    public function __construct(EloquentAttributeToggler $attributeToggler, PossibleVariants $possibleVariants)
    {
        parent::__construct(new Callback, $attributeToggler, $possibleVariants);
    }

    public function findByIdOrFail($id)
    {
        return $this->modelInstance->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        $query = Callback::query();
        $this->scopeOrdered($query);

        return $query->get();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }


    public function byPage($page = 1, $limit = 20)
    {
        $query = Callback::query();
        $this->scopeOrdered($query);

        $orderList = $query
            ->skip($limit * ($page - 1))
            ->take($limit)->get();

        $total = Callback::count();

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'items' => $orderList,
        ];
    }

    public function getStatusVariants()
    {
        $variants = [];
        foreach (CallbackStatusConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.callback.status.{$c}");
        }

        return $variants;
    }

    public function getTypeVariants()
    {
        $variants = [];
        foreach (CallbackTypeConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.callback.callback_type.{$c}");
        }

        return $variants;
    }
}
