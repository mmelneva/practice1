<?php
return [
    'public_root' => public_path(),
    'storage_root' => storage_path() . '/fileclip',
    'watermark_image' => public_path() . '/watermark.png', // local path to watermark image
    'imagine_driver' => 'auto', // imagine driver for processing of images, available variants: "gd", "imagick", "auto"
    'name_generator' => 'through_numbering', // name generator for versions of files, available variants: "original", "random", "through_numbering"
    'filename_prefix' => '', // prefix for generated file names when used "through numbering" name generator
    'models_root' => app_path('models'), // local path to directory with models used for update attachments by default
];
