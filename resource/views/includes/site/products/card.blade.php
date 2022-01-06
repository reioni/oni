<!-- product -->
<div class="col-md-4 col-xs-6">
	<div class="product">
		<div class="product-img">
			<img src="{{ url('storage/app/public/' . $product->firstImage) }}" alt="{{ $product->name }}" title="{{ $product->name }}">
			<div class="product-label">
				@if($product->getDiscount(1) > 0)
				<span class="sale">-{{ $product->getDiscount(1) }}% off</span>
				@endif

				@if(isset($new))
				<span class="new">NOVO</span>
				@endif
			</div>
		</div>
		<div class="product-body">
			<h3 class="product-name"><a href="{{ route('site.products.show', ['slug' => $product->slug]) }}" title="{{ $product->name }}">{{ $product->name }}</a></h3>
			<h4 class="product-price">
				R$ {{ $product->priceFormat }}
				@if(!empty($product->price_previous))
				<del class="product-old-price">{{ $product->pricePreviousFormat }}</del>
				@endif
			</h4>
			<div class="product-rating">
				@if($product->freight_free)
				<span class="product-available" style="margin-left: 0px;">Frete Grátis</span>
				@endif
				@if($product->ratings_active)
					@if($product->ratings->where('visible', true)->count() > 0)
						@for($i = 0; $i < $product->ratings->where('visible', true)->avg('stars'); $i++)
						<i class="fa fa-star"></i>
						@endfor

						@for($i = 0; $i < 5 - $product->ratings->where('visible', true)->avg('stars'); $i++)
						<i class="fa fa-star-o"></i>
						@endfor
					@else
					<p>Nenhuma Avaliação</p>
					@endif
				@endif
			</div>
			<div class="product-btns">
				<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">Favoritar</span></button>
				<button class="quick-view"><a href="{{ route('site.products.show', ['slug' => $product->slug]) }}" title="Visualizar"><i class="fa fa-eye"></i><span class="tooltipp">Visualizar</span></a></button>
			</div>
		</div>
		<div class="add-to-cart">
			@if($product->sizes->where('quantity', '>', 0)->count())
			<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</button>
			@else
			<p style="color: white;"><strong>Produto Indisponível</strong></p>
			@endif
		</div>
	</div>
</div>
<!-- /product -->