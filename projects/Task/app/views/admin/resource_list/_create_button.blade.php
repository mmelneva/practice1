@if(empty($no_create))
    <div>
        <a href="{{{ wrap_with_paginator(action($resource_controller . '@getCreate'), $resource_list) }}}" class="btn btn-success btn-xs">{{{ array_get($resource_texts, 'add_new', 'Добавить') }}}</a>
    </div>
@endif