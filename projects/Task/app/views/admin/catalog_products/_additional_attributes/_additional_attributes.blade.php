<div class="form-group">
    {{ Form::tbFormGroupOpen('additional_attributes') }}
        {{ Form::label('Дополнительные параметры') }}
    {{ Form::tbFormGroupClose() }}

    <div class="dashed-list-container additional-attributes">
        <ul class="enabled-attributes">
            @foreach ($additional_attributes_enabled as $attribute_container)
                @include('admin.catalog_products._additional_attributes._attribute', $attribute_container)
            @endforeach
        </ul>

        @if (count($additional_attributes_disabled) > 0)
        <span class="toggle-other">Добавить параметры</span>
        <ul class="disabled-attributes">
            @foreach ($additional_attributes_disabled as $attribute_container)
                @include('admin.catalog_products._additional_attributes._attribute', $attribute_container)
            @endforeach
        </ul>
        @endif
    </div>
</div>