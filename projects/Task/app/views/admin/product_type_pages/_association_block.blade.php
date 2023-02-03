<div class="association {{ isset($class) ? $class : '' }}">
    <input type="hidden" name="{{{ "associated_products[{$type}][{$productId}][opened]" }}}" value="1" />

    {{ Form::tbFormGroupOpen("associated_products.{$type}.{$productId}.name") }}
    {{ Form::tbLabel("associated_products[{$type}][{$productId}][name]", trans('validation.attributes.name')) }}
    {{ Form::tbText("associated_products[{$type}][{$productId}][name]", object_get($association, 'name')) }}
    {{ Form::tbFormGroupClose() }}

    {{ Form::tbFormGroupOpen("associated_products.{$type}.{$productId}.position") }}
    {{ Form::tbLabel("associated_products[{$type}][{$productId}][position]", trans('validation.attributes.position')) }}
    {{ Form::tbText("associated_products[{$type}][{$productId}][position]", object_get($association, 'position')) }}
    {{ Form::tbFormGroupClose() }}

    {{ Form::tbFormGroupOpen("associated_products.{$type}.{$productId}.small_content") }}
    {{ Form::tbLabel("associated_products[{$type}][{$productId}][small_content]", trans('validation.attributes.small_content')) }}
    {{ Form::tbTextarea("associated_products[{$type}][{$productId}][small_content]", object_get($association, 'small_content')) }}
    {{ Form::tbFormGroupClose() }}

</div>