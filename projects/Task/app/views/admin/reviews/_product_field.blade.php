{{ Form::tbFormGroupOpen('product_id') }}
    {{ Form::tbLabel('product_id', trans('validation.attributes.product_id')) }}
    {{ Form::tbSelect2('product_id', $productVariants) }}
{{ Form::tbFormGroupClose() }}

<fieldset class="bordered-group" id="product-type-tabs">
    {{ Form::tbLegend('Показывать отзыв на страницах категорий') }}

    <div id="product-association-container" class="product-association-container">
        <div class="">
            <div class="checkbox-tree manual">
                @include('admin.reviews._catalog_tree', [
                    'tree' => $catalogTree,
                    'field' => [
                        'field' => 'associated_categories',
                        'list' => 'associated_categories_variants',
                        'checked' => 'attached_associated_categories',
                    ]])
            </div>
        </div>

    </div>
</fieldset>

<fieldset class="bordered-group" id="product-type-tabs">
    {{ Form::tbLegend('Показывать отзыв на страницах тип товаров') }}

    <div id="product-association-container" class="associated_product_type_pages">
        <div class="">
            <div class="checkbox-tree manual">
                @include('admin.reviews._catalog_tree', [
                    'tree' => $pagesTree,
                    'field' => [
                        'field' => 'associated_product_type_pages',
                        'list' => 'associated_product_type_pages_variants',
                        'checked' => 'attached_associated_product_type_pages',
                    ]])
            </div>
        </div>

    </div>
</fieldset>

