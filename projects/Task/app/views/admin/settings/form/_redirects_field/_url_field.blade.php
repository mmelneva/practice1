@include('admin.settings.form._redirects_field._text_field', [
    'value' => !empty($url) ? $url : null,
    'field' => 'url',
])