{{-- template to show gallery image for product --}}

<li class="{{ (!$image->exists ? 'new' : '') . (in_array($image->id, $opened_images) ? ' expanded' : '') }}">
    <input class="opened-hidden" type="hidden" name="{{{ "images[{$image->id}][opened]" }}}" value="{{{ in_array($image->id, $opened_images) ? 1 : 0 }}}" />
    @if ($image->exists)
        <div class="image-fields-toggle">
            @include('admin.shared._list_flag', [
            'element' => $image,
            'action' => action('App\Controllers\Admin\CatalogProductGalleryController@putToggleAttribute', [$image->id, 'publish']),
            'attribute' => 'publish'])

            @include('admin.catalog_products._gallery_images._gallery_image_remove_container', ['image' => $image])
            <img src="{{{ $image->getAttachment('image')->getRelativePath('thumb') }}}" />

            <span class="title">{{{ array_get($image, 'name') }}}</span>
        </div>
    @endif
    <div class="image-fields-wrapper">

        @include('admin.catalog_products._gallery_images._gallery_image_remove_container', ['image' => $image])

        {{ Form::tbFormGroupOpen("images.{$image->id}.image_file") }}
        {{ Form::tbLabel("images.{$image->id}.image_file", trans("validation.attributes.image_file")) }}
        <span class="collapse">свернуть</span>
        @if (!is_null($image->image))
        <div class="loaded-image">
            <a href="{{{ $image->getAttachment('image')->getRelativePath() }}}" target="_blank" rel="prettyPhoto">
                <img src="{{{ $image->getAttachment('image')->getRelativePath('thumb') }}}" />
            </a>
        </div>
        @endif
        <div class="file-upload-container">
            {{ Form::file(scope_field_name("image_file", ['images', $image->id])) }}
        </div>
        {{ Form::tbFormGroupClose() }}

        @include('admin.catalog_products._gallery_images._gallery_image_fields', ['image' => $image])

        @if ($image->exists)
        <label>
            <input type="checkbox" name="{{{ "images[{$image->id}][delete]" }}}" />
            удалить
        </label>
        @endif
    </div>
</li>