<div class="sorting-control">
    <span>Сортировка:</span>
    <button data-sortable-update-positions="{{{ action($resource_controller . '@putUpdatePositions') }}}"
            class="btn btn-primary btn-xs">Сохранить сортировку</button>
    <button data-sortable-refresh-list="{{{ action($resource_controller . '@getIndex') }}}"
            class="btn btn-default btn-xs">Вернуть к исходному варианту</button>
</div>