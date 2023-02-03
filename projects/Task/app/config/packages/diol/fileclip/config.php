<?php
return [
    'public_root'  =>  public_path(),
    'storage_root'    =>  storage_path() . '/fileclip',
    'watermark_image' => public_path() . '/watermark.png', // local path to watermark image
    'update_attachments_models' => [
        'App\Models\CatalogProduct',
        'App\Models\ProductGalleryImage',
    ], // list with classes of eloquent models used for update attachments by default
];

