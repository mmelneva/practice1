{{-- template to show gallery image list for product --}}

<ul class="gallery-images">
    @foreach ($images as $image)
        @include('admin.catalog_products._gallery_images._gallery_image', ['image' => $image, 'opened_images' => $opened_images])
    @endforeach
</ul>