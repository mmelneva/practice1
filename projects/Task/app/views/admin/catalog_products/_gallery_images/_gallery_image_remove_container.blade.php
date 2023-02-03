<div class="remove-container">
    @if($image->exists)
        <a href="{{{ action('App\Controllers\Admin\CatalogProductGalleryController@deleteDestroy', [$image->id]) }}}"
           data-method="delete"
           data-confirm="Вы уверены, что хотите удалить изображение?"
           class="remove glyphicon glyphicon-remove"
           title="удалить"></a>
    @else
        <a href="#"
           class="remove-new glyphicon glyphicon-remove"
           title="удалить"></a>
    @endif
</div>