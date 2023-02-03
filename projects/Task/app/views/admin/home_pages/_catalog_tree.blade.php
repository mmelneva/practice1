<ul>
    @foreach ($catalogTree as $category)
        <li class="group">
            <input type="checkbox" class="group-checkbox" />
            <span class="group-toggle">{{{ $category->name }}}</span>

            <div class="group-content
            {{ $errors->has() && is_array($oldAssociatedProducts = \Input::old("associated_products", []))
             && count($oldAssociatedProducts) > 0 ? 'visible' : '' }}">
                @if (count($category->children) > 0)
                    @include('admin.home_pages._catalog_tree', ['catalogTree' => $category->children])
                @endif

                @if (count($category->products) > 0)
                    <ul>
                        @foreach ($category->products as $product)
                            <li>
                                {{ Form::checkbox("associated_products[{$product->id}][manual]", 1, array_get($associations, $product->id), ['class' => 'element-checkbox']) }}
                                <span class="product-name" data-product-association-url="{{{ action('App\Controllers\Admin\HomePagesController@getProductAssociationBlock', [$product->id, $homePage->id]) }}}">{{{ $product->name }}}</span>

                                @if (\Input::old("associated_products.{$product->id}.opened"))
                                    @include('admin.product_type_pages._association_block', ['association' => array_get($associations, $product->id), 'productId' => $product->id, 'class' =>'visible'])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </li>
    @endforeach
</ul>