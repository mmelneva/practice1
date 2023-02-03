@if($resource->exists)
{{ Form::tbFormGroupOpen() }}
<strong>{{{ trans('validation.model_attributes.order.product') }}}</strong>:
    @if(!is_null($resource->product))
        <a href="{{{ UrlBuilder::getUrl($resource->product) }}}">{{ $resource->product->name }}</a>
        (<a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getEdit', [$resource->product->id]) }}}">cc</a>)
    @else
        {{ $resource->product_name }}
    @endif
{{ Form::tbFormGroupClose() }}
@endif