<ul>
    @foreach ($tree as $item)

        {{ ''; $opt = ['class' => 'element-checkbox']; $spanClass = ''; $itemId = $item->id; }}
        @if($item instanceof \App\Models\Node && $item->productTypePage()->count() == 0)

            @if($item->children()->count() == 0)
                @continue
            @endif

            {{ ''; $opt = array(/*'disabled' => '',*/ 'class'=>'group-checkbox');
                  $itemId = 0;
                  $spanClass = 'disabled group-toggle'; }}

        @elseif($item instanceof \App\Models\Node)

            @if($item->children()->count() > 0)
                {{ ''; $spanClass = 'group-toggle'; $opt = ['class'=>'group-checkbox'] }}
            @endif

            {{ ''; $itemId = $item->productTypePage()->first()->id; }}
        @endif

        <li class="group">
            {{ Form::checkbox("{$field['field']}[$itemId]", 'on', in_array($itemId, ${$field['checked']}), $opt) }}
            <span class="{{$spanClass}}">{{{ $item->name }}}</span>

            @if($item->children()->count()>0)
                <div class="group-content {{ $errors->has() && is_array($oldAssociatedProducts = \Input::old("associated_products", []))
             && count($oldAssociatedProducts) > 0 ? 'visible' : '' }}" >
                @include('admin.reviews._catalog_tree', [
                    'tree' => $item->children()->get(),
                    'field' => [
                        'field' => 'associated_product_type_pages',
                        'list' => 'associated_product_type_pages_variants',
                        'checked' => 'attached_associated_product_type_pages',
                    ]])
                </div>
            @endif


        </li>
    @endforeach
</ul>