{{-- Form fields for category --}}

{{--{{ Form::tbSelectBlock('parent_id', $parentVariants) }}--}}
{{ Form::hidden('parent_id') }}

{{ Form::tbTextBlock('name') }}

{{ Form::tbCheckboxBlock('publish') }}

{{ Form::tbCheckboxBlock('top_menu') }}

{{ Form::tbCheckboxBlock('order_icon_type') }}

{{ Form::tbCheckboxBlock('use_reviews_associations') }}

{{ Form::tbTextBlock('position') }}

{{ Form::tbTextBlock('alias') }}
{{ Form::tbTextBlock('header') }}

@include('admin.shared._model_image_field', ['model' => $category, 'field' => 'logo'])
@include('admin.shared._model_image_field', ['model' => $category, 'field' => 'logo_active', 'label' => trans("validation.model_attributes.catalog_category.logo_active_file")])

{{ Form::tbTextBlock('similar_products_block_name') }}

{{ Form::tbTinymceTextareaBlock('content') }}
{{ Form::tbTinymceTextareaBlock('content_bottom') }}
{{ Form::tbTinymceTextareaBlock('content_for_submenu') }}
{{ Form::tbTinymceTextareaBlock('content_for_sidebar') }}

@include('admin.shared._form_meta_fields')
@include('admin.shared._model_timestamps', ['model' => $category])