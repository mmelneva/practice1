<fieldset class="bordered-group" id="product-type-tabs">
    {{ Form::tbLegend('Товары') }}

    <div>
        <label class="radio-inline">
            {{ Form::radio('product_list_way', \App\Models\ProductTypePage::WAY_MANUAL) }}
            {{ Form::hidden('product_list_way', \App\Models\ProductTypePage::WAY_MANUAL) }}
            Вручную из списка
        </label>

        <label class="radio-inline">
            {{ Form::radio('product_list_way', \App\Models\ProductTypePage::WAY_FILTERED) }}
            По запросу фильтра
        </label>
    </div>

    <div id="product-association-container" class="product-association-container">
        <div class="tab {{{ \Input::old('product_list_way', $productTypePage->product_list_way) == \App\Models\ProductTypePage::WAY_MANUAL ? 'active' : '' }}}"
             id="product-association-{{{ \App\Models\ProductTypePage::WAY_MANUAL }}}">

            {{ Form::tbSelectBlock('manual_product_list_category_id', $rootCategoryVariants, null, $manualCategorySelectedVariant) }}

            <div class="checkbox-tree manual">
                @include('admin.product_type_pages._catalog_tree')
            </div>
        </div>

        <div class="tab {{{ \Input::old('product_list_way', $productTypePage->product_list_way) == \App\Models\ProductTypePage::WAY_FILTERED ? 'active' : '' }}}"
             id="product-association-{{{ \App\Models\ProductTypePage::WAY_FILTERED }}}">
            <div class="filtered">
                @include('admin.product_type_pages._filter_block')
            </div>
        </div>
    </div>
</fieldset>