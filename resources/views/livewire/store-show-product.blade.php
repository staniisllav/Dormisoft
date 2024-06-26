<div id="store-show-product">

	<div class="breadcrumbs container">
		<a class="breadcrumbs__link" href="{{ url("/") }}">
			Acasă
		</a>
		@if (app()->has("global_show_on_breadcrumbs") && app('global_show_on_breadcrumbs') == 'true')

		<a class="breadcrumbs__link" href="{{ url("/storeproducts") }}">
			Toate produsele
		</a>
		@endif
		@if ($product->product_categories->isNotEmpty())
			@foreach ($product->getCategoryHierarchy() as $breadcrumb)
				<a class="breadcrumbs__link" href="{{ route("products", ["categorySlug" => $breadcrumb["slug"]]) }}">
					{{ $breadcrumb["name"] }}
				</a>
			@endforeach
		@endif
		<a href="{{ route("product", ["product" => $product->seo_id !== null && $product->seo_id !== "" ? $product->seo_id : $product->id]) }}" class="breadcrumbs__link">{{ $product->name }}</a>
	</div>
	<section class="product container">
		<!-------------------- Slider Product ------------------>
		<div class="product-slider">
			<div class="product-slider__center">

				<div class="product-slider__wrapper">
					@if ($product->media->count() != 0)
						@foreach ($product->media->where("type", "full") as $media)
							<div class="product-slider__slide">
								<img loading="lazy" src="/{{ $media->path }}{{ $media->name }}" data-name-alt="{{ $media->name }}{{ $product->name }}" alt="{{ $media->name }}{{ $product->name }}" data-img-src="/{{ $product->media->where("type", "original")->where("sequence", $media->sequence)->first()->path }}{{ $product->media->where("type", "original")->where("sequence", $media->sequence)->first()->name }}">
							</div>
						@endforeach
					@else
						<div class="product-slider__slide">
							<img loading="lazy" src="/images/store/default/default.webp" data-img-src="/images/store/default/default.webp" alt="something wrong" data-name-alt="something wrong">
						</div>
					@endif
				</div>
				<div class="product-slider__navigation">
					<button class="product-slider__prev" aria-label="Previous slide">
						<svg>
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</button>
					<button class="product-slider__next" aria-label="Next slide">
						<svg>
							<polyline points="9 18 15 12 9 6"></polyline>
						</svg>
					</button>
				</div>
			</div>
			<div class="product-slider__pagination--navigation">
				<button class="product-slider__pagination--button product-slider__pagination--prev disabled" aria-label="Previous">
					<svg>
						<polyline points="15 18 9 12 15 6"></polyline>
					</svg>
				</button>
				<div class="product-slider__pagination">
				</div>
				<button class="product-slider__pagination--button product-slider__pagination--next disabled" aria-label="Next">
					<svg>
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</button>
			</div>
		</div>
		<!------------------ End Slider Product ---------------->
		<!------------------------------------------------------>
		<!-------------------- Modal Product ------------------>
		<div class="product-modal">
			<div class="product-modal__content"></div>
			<button class="product-modal__close">
				<svg>
					<polyline points="4 14 10 14 10 20"></polyline>
					<polyline points="20 10 14 10 14 4"></polyline>
					<line x1="14" y1="10" x2="21" y2="3"></line>
					<line x1="3" y1="21" x2="10" y2="14"></line>
				</svg>
			</button>
			<button class="product-modal__prev">
				<svg>
					<polyline points="15 18 9 12 15 6"></polyline>
				</svg>
			</button>
			<button class="product-modal__next">
				<svg>
					<polyline points="9 18 15 12 9 6"></polyline>
				</svg>
			</button>
			<span class="product-modal__count"></span>
		</div>
		<!------------------ End Modal Product ---------------->
		<!------------------------------------------------------>
		<!----------------------- Product details ---------------------->
		@livewire("product-details", ["product" => $product])
		<!--------------------- End Product details -------------------->
		<!------------------------------------------------------>
	</section>
  <h2></h2>
	<!---------------------------------------------------------->
	<!------------------- Section Description ------------------>
	@if ($product->related_product->filter(fn($item) => !is_null($item["product"]))->isNotEmpty())
		<section>
			<div class="section__header container">
				<h2 class="section__title">Descoperă și alte opțiuni similare</h2>
				<p class="section__text">
					În căutarea perfectă? Explorează și alte propuneri care te-ar putea interesa.
					Descoperă produse similare, perfecte pentru gusturile tale și nevoile tale. În continuare, vei găsi
					opțiuni care completează gama noastră și care îți pot satisface preferințele. Alege cu încredere
					dintre
					aceste alternative și găsește exact ceea ce cauți.
				</p>
			</div>
		</section>
		<!----------------- End Section Description ---------------->
		<!---------------------------------------------------------->
		<!---------------------- Slider Cards ---------------------->
		<section id="relatedSlider" class="related__slider container">
			{{-- <div class="related__navigation"> --}}
			<button class="related__btn prev" aria-label="Previous related slider">
				<svg>
					<line x1="19" y1="12" x2="5" y2="12"></line>
					<polyline points="12 19 5 12 12 5"></polyline>
				</svg>
			</button>
			<button class="related__btn next" aria-label="Next related slider">
				<svg>
					<line x1="5" y1="12" x2="19" y2="12"></line>
					<polyline points="12 5 19 12 12 19"></polyline>
				</svg>
			</button>
			{{-- </div> --}}
			<div class="related__wrapper">
				@foreach ($product->related_product->sortByDesc("product.popularity") as $product)
				@if (($product->product) && ($product->product->active == true) && ($product->product->end_date >=  now()->format('Y-m-d')) && ($product->product->start_date <=  now()->format('Y-m-d')))


				<div class="card">
					<a href="{{ route("product", ["product" => $product->product->seo_id !== null && $product->product->seo_id !== "" ? $product->product->seo_id : $product->product->id]) }}">

						@if ($product->product->media->first() != null)
							<img loading="lazy" class="card-image" src="/{{ $product->product->media->first()->path }}{{ $product->product->media->first()->name }}" alt="{{ $product->product->media->first()->name }} {{ $product->product->name }}">
						@else
							<img loading="lazy" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
						@endif
					</a>
					@livewire("product-wishlist-button", ["productId" => $product->product->id, "class" => "card__action", "is_in_wishlist" => $product->product->wishlists->isNotEmpty()], key($product->product->id))
					<?php if ($product->product->product_prices->count() != 0) {
						$price = number_format($product->product->product_prices->first()->value, 2, ",", ".");
						$discount = $product->product->product_prices->first()->discount != 0 ? true : false;
					} else {
						$price = null;
						$discount = false;
					}
					?>
					@if ($price)
						@if ($product->product->quantity < $quantity && $product->product->quantity > 0)
							<p class="card-status out">
								Stock limitat!!
							</p>
							@if ($discount)
								<p class="card-status save-secondary">
									-{{ $product->product->product_prices->first()->discount }}%
								</p>
							@endif
						@elseif($product->product->quantity == 0)
							<p class="card-status save">
								Produs indisponibil!
							</p>
						@else
							@if ($discount)
								<p class="card-status save">
									-{{ $product->product->product_prices->first()->discount }}%
								</p>
							@endif
						@endif
						{{-- tagul de discount --}}
					@else
						<p class="card-status save">
							În curând!
						</p>
					@endif
					<div class="card-info">
						<div class="card-text">
							<span>{{ $product->product->short_description }}</span>
						</div>
						<div class="card-text">
							<h3>{{ $product->product->name }}</h3>
							<p class="card-price">
								@if ($discount)
									<span class="card-price discount">
										@if ($product->product->product_prices->first())
											{{ $price }}
											{{ $product->product->product_prices->first()->pricelist->currency->symbol }}
										@endif
									</span>
									<span class="card-price oldprice">
										{{ $product->product->product_prices->first()->rrp_value }}
										{{ $product->product->product_prices->first()->pricelist->currency->name }}
									</span>
								@else
									<span>
										@if ($product->product->product_prices->first())
											{{ $price }}
											{{ $product->product->product_prices->first()->pricelist->currency->symbol }}
										@endif
									</span>
								@endif

							</p>
						</div>
						@if ($price)
							@livewire("add-to-cart-button", ["product" => $product->product], key($product->product->id))
						@else
							<a class="card-button-disabled" onclick="handleClick()" aria-label="Indisponibil">Indisponibil</a>
						@endif
					</div>
				</div>
				@endif
				@endforeach
			</div>
		</section>
	@endif

	<!-------------------- End Slider Cards -------------------->
	<!---------------------------------------------------------->
	<!--------------------- support button --------------------->
	<x-help-button />
	<!------------------- End support button ------------------->
	<!---------------------------------------------------------->
	<script src="/script/store/product.js" async defer></script>
</div>
