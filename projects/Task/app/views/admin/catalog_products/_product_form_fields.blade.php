{{ Form::tbSelectBlock('category_id', $categoryVariants) }}

@include('admin.resource_fields._multi_checkbox_field', [
        'field' => [
            'field' => 'associated_categories',
            'list' => 'associated_categories_variants',
            'checked' => 'attached_associated_categories',
        ]
    ])

{{ Form::tbTextBlock('name') }}
{{ Form::tbCheckboxBlock('publish') }}
{{ Form::tbCheckboxBlock('no_template_text') }}

{{ Form::tbTextBlock('position') }}

{{ Form::tbTextBlock('header') }}

{{ Form::tbTinymceTextareaBlock('content') }}

{{ Form::tbTextareaBlock('small_content')}}

@include('admin.shared._model_image_field', ['model' => $product, 'field' => 'preview_image'])
@include('admin.shared._model_image_field', ['model' => $product, 'field' => 'image'])

@include('admin.catalog_products._gallery_images._gallery_image_list_container', ['images' => $images])

{{ Form::tbFormGroupOpen('price') }}
    {{ Form::tbLabel('price', trans('validation.attributes.price')) }}
    {{ Form::tbText('price') }}
{{ Form::tbFormGroupClose() }}

{{ Form::tbSelectBlock('built_in', $builtInVariants) }}

@include('admin.catalog_products._additional_attributes._additional_attributes')

@include('admin.shared._form_meta_fields')
@include('admin.shared._model_timestamps', ['model' => $product])