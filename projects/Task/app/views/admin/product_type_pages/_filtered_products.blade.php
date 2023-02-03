@if (count($filteredProducts) > 0)
    <ol>
        @foreach ($filteredProducts as $product)
            <li>
                <span class="product-name"
                      data-product-association-url="{{{ action('App\Controllers\Admin\ProductTypePagesController@getProductAssociationBlock', ['filtered', $product->id, $productTypePageId]) }}}">{{{ $product->name }}}</span>

                @if (\Input::old("associated_products.filtered.{$product->id}.opened"))
                    @include('admin.product_type_pages._association_block', ['type' => 'filtered', 'association' => array_get($associations, $product->id), 'productId' => $product->id, 'class' => 'visible'])
                @endif
            </li>
        @endforeach
    </ol>
@else
    <strong>Не найдено ни одного товара</strong>
@endif