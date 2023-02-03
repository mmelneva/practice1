@if ($paginator instanceof Illuminate\Pagination\Paginator)
    <div class="pagination-container">
        {{ $paginator->links()->render() }}
    </div>
@endif