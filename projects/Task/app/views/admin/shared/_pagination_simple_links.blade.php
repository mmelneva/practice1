@if ($paginator instanceof Illuminate\Pagination\Paginator)
    <div class="pagination-container">
        {{ $paginator->links('pagination::simple')->render() }}
    </div>
@endif