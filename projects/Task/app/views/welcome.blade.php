<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel</title>
{{ Asset::includeCss('client_css') }}
{{ Asset::includeJS('client_js') }}
</head>
<body>
<h1>каталог продуктов по категориям</h1>
<div class="tab">
[club65537571|$categoryTree as $category]
<button class="tablinks" onclick="openCategory(event, '{{{ $category->id }}}')">{{{ $category->name }}}</button>
@endforeach
</div>

<div class="container">
<div class="row">
<div class="col-6 main">
[club65537571|$products as $product]
<div class="product-item" name="{{{ $product->category_id }}}">

<!— <div class="product-img">
<a href="">
<img src="https://html5book.ru/wp-content/uploads/2015/10/black-dress.jpg">
</a>
</div> —>

<div class="product-list">
[id147056226|strlen($product->name] > 50)
<h3>{{{ mb_substr($product->name, 0, 50) }}}...</h3>
@else
<h3>{{{ $product->name }}}</h3>
@endif
<div class="stars"></div>
<span class="price"> ₽ {{{ $product->price }}}</span>
<div class="actions">
<div class="add-to-cart">
<a href="" class="cart-button">В корзину</a>
</div>
<div class="add-to-links">
<a href="" class="wishlist"></a>
<a href="" class="compare"></a>
</div>
<div class="id_item">catId => {{{ $product->category_id }}}</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>

</body>
</html>
