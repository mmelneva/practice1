<ul class="element-list" data-sortable-group="">
    @foreach ($resource_list as $resource)
        <li data-element-id="{{{ $resource->id }}}">
            <div class="element-container">
                @include('admin.resource_list_sortable._list_sorting_controls')
                @foreach ($list_columns as $column)
                    @include($column['template'], ['column' => $column])
                @endforeach
            </div>
        </li>
    @endforeach
</ul>