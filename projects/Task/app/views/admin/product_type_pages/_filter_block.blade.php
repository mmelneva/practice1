{{--{{ Form::tbSelectBlock('default_filter_category_id', $rootCategoryVariants) }}--}}

{{ Form::tbFormGroupOpen('filter_query') }}
{{ Form::tbLabel('filter_query', trans('validation.attributes.filter_query')) }}
<div class="field-hint-block" style="color: #af294d">
    Для формирования строки фильтра перейдите на страницу <a target="_blank" href="{{ route('full_catalog') }}">{{ route('full_catalog') }}</a>
</div>
{{ Form::tbText('filter_query') }}
<div class="filter-query-hint">
    <strong>Например:</strong>
    https://lit-mebel.ru/cat?filter[built_in]=1
</div>
{{ Form::tbFormGroupClose() }}


<div class="update-products-container">
    Условия фильтра были изменены, вам необходимо
    <a href="{{{ action('App\Controllers\Admin\ProductTypePagesController@getFilteredProducts', [$productTypePage->id]) }}}">обновить</a>
    список товаров.
</div>


<span class="show-hide-products">Товары</span>
<div class="products">
    @include('admin.product_type_pages._filtered_products', ['productTypePageId' => $productTypePage->id])
</div>