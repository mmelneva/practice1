<div class="control">
    @include(
        'admin.shared._element_list_controls',
        [
            'element' => $resource,
            'controller' => $resource_controller,
            'delete_confirm' => array_get($resource_texts, 'delete_confirm', 'Вы уверены, что хотите удалить?'),
            'disable_delete' => array_get($resource, 'disallow_delete', false),
        ]
    )
</div>