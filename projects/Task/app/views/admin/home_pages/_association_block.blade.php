<div class="association {{ isset($class) ? $class : '' }}">
    <input type="hidden" name="{{{ "associated_products[{$productId}][opened]" }}}" value="1" />

    {{ Form::tbFormGroupOpen("associated_products.{$productId}.position") }}
    {{ Form::tbLabel("associated_products[{$productId}][position]", trans('validation.attributes.position')) }}
    {{ Form::tbText("associated_products[{$productId}][position]", object_get($association, 'position')) }}
    {{ Form::tbFormGroupClose() }}

</div>