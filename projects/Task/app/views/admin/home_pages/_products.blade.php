<fieldset class="bordered-group" id="product-type-tabs">
    {{ Form::tbLegend('Товары') }}

    <div id="product-association-container" class="product-association-container">
        <div class="">
            <div class="checkbox-tree manual">
                @include('admin.home_pages._catalog_tree')
            </div>
        </div>

    </div>
</fieldset>