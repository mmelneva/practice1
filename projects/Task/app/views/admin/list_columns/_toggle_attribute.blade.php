@include('admin.shared._list_flag', ['element' => $resource, 'action' => action($resource_controller . '@putToggleAttribute', [$resource->id, $column['field']]), 'attribute' => $column['field']])