<?php namespace App\Models;

class ReviewsDateChange extends \Eloquent
{
    protected $fillable = ['iteration', 'old_value', 'new_value'];
    protected $dates = ['old_value', 'new_value'];

    public function reviews()
    {
        return $this->belongsTo('App\Models\Reviews');
    }
}
