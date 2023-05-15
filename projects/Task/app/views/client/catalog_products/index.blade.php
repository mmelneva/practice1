<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>lit-mebel</title>
{{ Asset::includeCss('client_css') }}
{{ Asset::includeJS('client_js') }}
</head>
<body>

<h1 class="mb-1 mt-1 ml-4">Список товаров</h1>
<div class="tab mb-2 nav-bar">
<div class="ml-3">
@foreach($categoryTree as $category)
	<button class="btn btn-link" onclick="openCategory(event, '{{{ $category->id }}}')">{{{ $category->name }}}</button>
@endforeach

</div>
</div>

<div class="my-container mx-1">
<div class="my-row">
<div class="col-6 main">

@foreach($products as $product)
<div class="product-item card mr-1" name="{{{ $product->category_id }}}">

<div class="card-body">
<h5 class="card-title">
@if (strlen($product->name > 50))
{{{ mb_substr($product->name, 0, 50) }}}...
@else
{{{ $product->name }}}
@endif
</h5>
<h6 class="card-subtitle mb-2 text-muted"> ₽ {{{ $product->price }}}</h6>
</div>
</div>
@endforeach
</div>
</div>
</div>

</body>
</html>
