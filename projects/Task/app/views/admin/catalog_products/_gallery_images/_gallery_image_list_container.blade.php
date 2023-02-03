<div class="form-group">
    <label>{{{ trans('validation.attributes.product_gallery_images') }}}</label>
    <div class="gallery-images-container">
        @include('admin.catalog_products._gallery_images._gallery_image_list', ['images' => $images])
        <div class="add-new">
            <a href="{{{ action('App\Controllers\Admin\CatalogProductGalleryController@getNewImageBlock') }}}">Добавить новое изображение</a>
            <img src="/images/common/ajax-loader/small_black.gif" />
        </div>
    </div>
</div>