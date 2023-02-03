<?php namespace App\Services\Repositories\ReviewsDateChange;

use App\Models\ReviewsDateChange;
use App\Models\Reviews;
use Carbon\Carbon;

class EloquentReviewsDateChangeRepository implements ReviewsDateChangeRepositoryInterface
{
    public function getLastIteration()
    {
        $iteration = ReviewsDateChange::max('iteration');
        if (is_null($iteration)) {
            $iteration = 0;
        }

        return $iteration;
    }


    public function create(Reviews $review, $iteration, Carbon $oldValue, Carbon $newValue)
    {
        $review->dateChanges()->create([
            'iteration' => $iteration,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }


    public function countWithinIteration($iteration)
    {
        return ReviewsDateChange::query()->where('iteration', $iteration)->count();
    }


    public function allWithinIteration($iteration)
    {
        return ReviewsDateChange::query()
            ->where('iteration', $iteration)->orderBy('new_value', 'desc')
            ->with('reviews')
            ->get();
    }
}
