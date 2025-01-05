<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/productdetails.css') }}" rel="stylesheet">
    <link href="{{ asset('css/productdetails2.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .add-to-cart:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
        .variation-option.selected, .size-option.selected {
            border-color: #ee4d2d;
            color: #ee4d2d;
        }
        .size-option.out-of-stock {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <nav class="breadcrumb">
        <a href="{{ route('users.home') }}" class="breadcrumb-item">Shopee</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="" class="breadcrumb-item">{{ $product->category->name }}</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="" class="breadcrumb-item">{{ $product->subCategory->name }}</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="" class="breadcrumb-item">{{ $product->item->name }}</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="" class="breadcrumb-item" style="color: black;">{{ $product->name }}</a>
    </nav>

    <div class="container">
        <div class="product">
            <div class="gallery">
                <img src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                <div class="thumbnails-container">
                    <button class="arrow arrow-left" id="arrowLeft"><i class="fas fa-chevron-left"></i></button>
                    <div class="thumbnails" id="thumbnails">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="thumbnail" data-color="white">
                        @endforeach
                    </div>
                    <button class="arrow arrow-right" id="arrowRight"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                <div class="rating">
                    <div class="stars">★★★★☆</div>
                    <span>4.5 (3.5K Ratings)</span>
                </div>
                <div class="price">
                    ₱{{ number_format($product->price, 2) }}
                    @if($product->discount_percentage > 0)
                        <span class="original-price">₱{{ number_format($product->price / (1 - $product->discount_percentage / 100), 2) }}</span>
                        <span class="discount">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>
                @php
                    $allSizes = collect();
                    foreach ($product->variations as $variation) {
                        foreach ($variation->options as $option) {
                            $allSizes = $allSizes->concat($option->sizes);
                        }
                    }
                    $uniqueSizes = $allSizes->unique('name')->sortBy('name');
                @endphp
                @foreach($product->variations as $variation)
                    <div class="variation">
                        <h3 class="label-text">{{ $variation->name }}</h3>
                        <div class="variation-options" id="variationOptions{{ $variation->id }}">
                            @foreach($variation->options as $option)
                                <div class="variation-option text" data-value="{{ $option->name }}" data-option-id="{{ $option->id }}" data-variation-id="{{ $variation->id }}">
                                    <span>{{ $option->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @if($uniqueSizes->isNotEmpty())
                    <div class="size-options">
                        <h3 class="label-text">Size</h3>
                        <div class="size-option-group">
                            @foreach($uniqueSizes as $size)
                                <div class="size-option" data-size-name="{{ $size->name }}" data-stock="{{ $size->stock }}">
                                    <span>{{ $size->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="quantity">
                    <h3 class="label-text">Quantity</h3>
                    <div class="quantity-controls" style="display: flex; align-items: center;">
                        <button id="decreaseQuantity" aria-label="Decrease quantity">-</button>
                        <input type="number" value="1" min="1" id="quantityInput" aria-label="Quantity">
                        <button id="increaseQuantity" aria-label="Increase quantity">+</button>
                        <span id="stockDisplay" style="margin-left: 12px; color: #757575; font-size: 14px;">{{ $product->stock }} pieces available</span>
                    </div>
                </div>
                <div class="actions">
                    <form action="{{ route('users.cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="quantity" id="quantity" value="1">
                        <input type="hidden" name="variations" id="variations" value="">
                        <input type="hidden" name="variation_option_id" id="variation_option_id" value="">
                        <input type="hidden" name="variation_option_size_id" id="variation_option_size_id" value="">
                        <input type="hidden" name="stock" id="stock" value="{{ $product->stock }}">
                        <button type="submit" class="add-to-cart" id="addToCartButton"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                    </form>
                    <button class="buy-now small-button"><i></i> Buy Now</button>
                </div>
            </div>
        </div>
    </div>

    <div class="specifications-container">
        <div class="specs-section">
            <h2>Product Specifications</h2>
            <div class="specs-grid">
                <div class="specs-label">Category</div>
                <div class="specs-value">
                    <a href="#">{{ $product->category->name }}</a> &gt;
                    <a href="#">{{ $product->subCategory->name }}</a> &gt;
                    <a href="#">{{ $product->item->name }}</a>
                </div>
                <div class="specs-label">Stock</div>
                <div class="specs-value">{{ $product->stock }}</div>
                <div class="specs-label">Brand</div>
                <div class="specs-value">
                    @if($product->brand)
                        {{ $product->brand->name }}
                    @else
                        No brand
                    @endif
                </div>
            </div>
        </div>

        <div class="specs-section">
            <h2>Product Description</h2>
            <div class="product-description">
                @php
                    $description = $product->description;
                    $sentences = preg_split('/(?<=[.!?])\s+/', $description, -1, PREG_SPLIT_NO_EMPTY);
                @endphp
                @foreach($sentences as $sentence)
                    <p>{{ $sentence }}</p>
                @endforeach
            </div>
        </div>

        <div class="specs-section">
            <h2>Product Ratings</h2>
            <div class="ratings-summary">
                <div class="rating-big">4.5</div>
                <div class="rating-text">out of 5</div>
            </div>
            <div class="rating-bars">
                <div class="rating-type active">All</div>
                <div class="rating-type">5 Star (2.9K)</div>
                <div class="rating-type">4 Star (492)</div>
                <div class="rating-type">3 Star (245)</div>
                <div class="rating-type">2 Star (87)</div>
                <div class="rating-type">1 Star (107)</div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnailsContainer = document.getElementById('thumbnails');
        const arrowLeft = document.getElementById('arrowLeft');
        const arrowRight = document.getElementById('arrowRight');
        const mainImage = document.getElementById('mainImage');
        const variationOptions = document.querySelectorAll('.variation-option');
        const sizeOptions = document.querySelectorAll('.size-option');
        const quantityInput = document.getElementById('quantityInput');
        const stockDisplay = document.getElementById('stockDisplay');
        const addToCartButton = document.getElementById('addToCartButton');
        const decreaseBtn = document.getElementById('decreaseQuantity');
        const increaseBtn = document.getElementById('increaseQuantity');
        const totalStock = {{ $product->stock }};
        const variationsInput = document.getElementById('variations');
        const variationOptionIdInput = document.getElementById('variation_option_id');
        const variationOptionSizeIdInput = document.getElementById('variation_option_size_id');
        const quantity = document.getElementById('quantity');
        const stockInput = document.getElementById('stock');

        let currentIndex = 0;
        let selectedVariations = {};
        let selectedSize = null;
        let selectedVariationOptionId = null;
        let selectedVariationOptionSizeId = null;

        // Check if product has variations or sizes
        const hasVariations = {{ $product->variations->isNotEmpty() ? 'true' : 'false' }};
        const hasSizes = document.querySelector('.size-options') !== null;

        function updateArrowVisibility() {
            arrowLeft.style.display = thumbnailsContainer.scrollLeft > 0 ? 'block' : 'none';
            arrowRight.style.display =
                thumbnailsContainer.scrollLeft < thumbnailsContainer.scrollWidth - thumbnailsContainer.clientWidth
                ? 'block' : 'none';
        }

        function scrollThumbnails(direction) {
            const scrollAmount = direction === 'left' ? -200 : 200;
            thumbnailsContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }

        function updateStockForVariationAndSize() {
            if (Object.keys(selectedVariations).length === {{ count($product->variations) }} && selectedSize) {
                let stock = 0;
                @foreach($product->variations as $variation)
                    @foreach($variation->options as $option)
                        if (selectedVariations['variationOptions{{ $variation->id }}'] === '{{ $option->name }}') {
                            @foreach($option->sizes as $size)
                                if (selectedSize === '{{ $size->name }}') {
                                    stock = {{ $size->stock }};
                                    selectedVariationOptionSizeId = {{ $size->id }};
                                }
                            @endforeach
                        }
                    @endforeach
                @endforeach
                stockDisplay.textContent = `${stock} pieces available`;
                stockInput.value = stock;
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
            }
        }

        function updateStockForVariationOnly() {
            if (Object.keys(selectedVariations).length === {{ count($product->variations) }}) {
                let stock = 0;
                @foreach($product->variations as $variation)
                    @foreach($variation->options as $option)
                        if (selectedVariations['variationOptions{{ $variation->id }}'] === '{{ $option->name }}') {
                            stock = {{ $option->stock }};
                            selectedVariationOptionId = {{ $option->id }};
                        }
                    @endforeach
                @endforeach
                stockDisplay.textContent = `${stock} pieces available`;
                stockInput.value = stock;
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
            }
        }

        function updateStockDisplay() {
            if (!hasVariations && !hasSizes) {
                // For products without variations and sizes
                stockDisplay.textContent = `${totalStock} pieces available`;
                stockInput.value = totalStock;
                quantityInput.max = totalStock;
            } else if (Object.keys(selectedVariations).length === {{ $product->variations->count() ?? 0 }}) {
                // For products with variations
                if (selectedSize) {
                    updateStockForVariationAndSize();
                } else {
                    updateStockForVariationOnly();
                }
            } else {
                stockDisplay.textContent = `${totalStock} pieces available`;
                stockInput.value = totalStock;
                quantityInput.max = totalStock;
            }
            updateAddToCartButton();
        }

        function updateSizeOptionsAvailability() {
            if (!hasVariations) return;

            sizeOptions.forEach(option => {
                const sizeName = option.getAttribute('data-size-name');
                let sizeStock = 0;

                @foreach($product->variations as $variation)
                    @foreach($variation->options as $option)
                        if (selectedVariations['variationOptions{{ $variation->id }}'] === '{{ $option->name }}') {
                            @foreach($option->sizes as $size)
                                if ('{{ $size->name }}' === sizeName) {
                                    sizeStock = {{ $size->stock }};
                                }
                            @endforeach
                        }
                    @endforeach
                @endforeach

                if (sizeStock === 0) {
                    option.classList.add('out-of-stock');
                    option.setAttribute('aria-disabled', 'true');
                } else {
                    option.classList.remove('out-of-stock');
                    option.removeAttribute('aria-disabled');
                }
            });
        }

        function updateAddToCartButton() {
            // For products without variations and sizes
            if (!hasVariations && !hasSizes) {
                const quantityValid = parseInt(quantityInput.value) > 0 &&
                    parseInt(quantityInput.value) <= totalStock;
                addToCartButton.disabled = !quantityValid;
                return;
            }

            // For products with variations and/or sizes
            const allVariationsSelected = hasVariations ?
                document.querySelectorAll('.variation-options').length === Object.keys(selectedVariations).length :
                true;
            const sizeSelected = hasSizes ? selectedSize !== null : true;
            const quantityValid = parseInt(quantityInput.value) > 0 &&
                (!quantityInput.max || parseInt(quantityInput.value) <= parseInt(quantityInput.max));

            addToCartButton.disabled = !(allVariationsSelected && sizeSelected && quantityValid);
        }

        function updateVariationsInput() {
            if (!hasVariations && !hasSizes) {
                variationsInput.value = JSON.stringify({});
                variationOptionIdInput.value = '';
                variationOptionSizeIdInput.value = '';
                return;
            }

            const variations = {};
            if (hasVariations) {
                document.querySelectorAll('.variation-options').forEach(variationOption => {
                    const variationId = variationOption.id;
                    const selectedOption = variationOption.querySelector('.variation-option.selected');
                    if (selectedOption) {
                        variations[variationId] = selectedOption.getAttribute('data-value');
                        selectedVariationOptionId = selectedOption.getAttribute('data-option-id');
                    }
                });
            }
            if (selectedSize) {
                variations['size'] = selectedSize;
            }
            variationsInput.value = JSON.stringify(variations);
            variationOptionIdInput.value = selectedVariationOptionId;
            variationOptionSizeIdInput.value = selectedVariationOptionSizeId;
        }

        // Event Listeners
        arrowLeft.addEventListener('click', () => scrollThumbnails('left'));
        arrowRight.addEventListener('click', () => scrollThumbnails('right'));
        thumbnailsContainer.addEventListener('scroll', updateArrowVisibility);
        window.addEventListener('resize', updateArrowVisibility);

        // Thumbnail click handlers
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                currentIndex = index;
                mainImage.src = thumbnail.src;
                thumbnails.forEach(t => t.classList.remove('active'));
                thumbnail.classList.add('active');
            });
        });

        // Variation option click handlers
        if (hasVariations) {
            variationOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const variationId = this.closest('.variation-options').id;
                    const optionValue = this.getAttribute('data-value');

                    selectedVariations[variationId] = optionValue;

                    this.closest('.variation-options').querySelectorAll('.variation-option').forEach(opt =>
                        opt.classList.remove('selected'));
                    this.classList.add('selected');

                    updateStockDisplay();
                    if (hasSizes) updateSizeOptionsAvailability();
                    updateVariationsInput();
                });
            });
        }

        // Size option click handlers
        if (hasSizes) {
            sizeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    if (!this.classList.contains('out-of-stock')) {
                        selectedSize = this.getAttribute('data-size-name');
                        sizeOptions.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');
                        updateStockDisplay();
                        updateVariationsInput();
                    }
                });
            });
        }

        // Quantity control handlers
        decreaseBtn.addEventListener('click', () => {
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
            updateStockDisplay();
        });

        increaseBtn.addEventListener('click', () => {
            const maxStock = parseInt(quantityInput.max) || totalStock;
            if (parseInt(quantityInput.value) < maxStock) {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            }
            updateStockDisplay();
        });

        quantityInput.addEventListener('input', () => {
            const maxStock = parseInt(quantityInput.max) || totalStock;
            if (parseInt(quantityInput.value) < 1) {
                quantityInput.value = 1;
            } else if (parseInt(quantityInput.value) > maxStock) {
                quantityInput.value = maxStock;
            }
            updateStockDisplay();
        });

        // Form submission handler
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            quantity.value = quantityInput.value;
            updateVariationsInput();

            if (hasSizes && !selectedSize && hasVariations) {
                alert('Please select a size before adding to cart.');
                return;
            }

            this.submit();
        });

        // Initialize
        updateArrowVisibility();
        updateStockDisplay();
        if (hasSizes && hasVariations) updateSizeOptionsAvailability();
    });
</script>
</body>
</html>
