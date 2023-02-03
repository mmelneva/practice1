<?php
namespace App\Models;

use App\Models\Features\AttachedToNode;

class TextPage extends \Eloquent
{
    use AttachedToNode;

    protected $fillable = [
        'header',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'short_content',
        'contact_form',
    ];
}
