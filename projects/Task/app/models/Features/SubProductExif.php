<?php namespace App\Models\Features;

use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExif\FileclipExif;

/**
 * Class SubProductExif
 * Trait to set exif for model which belongs to product via "product" association.
 *
 * @package App\Models\Features
 */
trait SubProductExif
{
    use FileclipExif;

    public static function bootSubProductExif()
    {
        static::setExifDataCallback(
            function ($model) {
                $product = $model->product;
                $productName = object_get($product, 'name');
                $productHeader = object_get($product, 'header');
                $productTitle = object_get($product, 'meta_title');
                if (empty($productHeader)) {
                    $productHeader = $productName;
                }
                if (empty($productTitle)) {
                    $productTitle = $productName;
                }

                return [
                    ExifDataWriter::TAG_DESCRIPTION => $productHeader,
                    ExifDataWriter::TAG_COMMENT => $productTitle,
                ];
            }
        );
    }
}
