<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopee Philippines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home2.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="banner-carousel">
        <div class="banner-sliding">
            <div class="banner-slide active">
                <img src="{{ asset('images/b1.png') }}" alt="Banner 1" class="banner-image">
                <div class="banner-content">
                </div>
            </div>
            <div class="banner-slide">
                <img src="{{ asset('images/b2.png') }}" alt="Banner 2" class="banner-image">
                <div class="banner-content">
                </div>
            </div>
            <div class="banner-slide">
                <img src="{{ asset('images/b3.png') }}" alt="Banner 3" class="banner-image">
                <div class="banner-content">
                </div>
            </div>
            <div class="banner-dots">
                <span class="banner-dot active"></span>
                <span class="banner-dot"></span>
                <span class="banner-dot"></span>
            </div>
        </div>
        <div class="banner-static">
            <img src="{{ asset('images/static1.png') }}" alt="Static Banner 1" class="banner-image static-image">
            <img src="{{ asset('images/static2.png') }}" alt="Static Banner 2" class="banner-image static-image">
        </div>
    </div>

    <section class="categories">
        <h2 class="categories-title">CATEGORIES</h2>
        <div class="categories-container">
            <div class="categories-row">
                @foreach($categories->take(10) as $category)
                    <a href="{{ route('category.show', $category->id) }}" class="category-item">
                        <div class="category-icon">
                            @if($category->category_image)
                                <img src="{{ asset('storage/' . $category->category_image) }}" alt="{{ $category->name }}">
                            @else
                                <i class='bx bx-package' style="font-size: 32px;"></i>
                            @endif
                        </div>
                        <span class="category-name">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
            <div class="categories-row">
                @foreach($categories->skip(10)->take(10) as $category)
                    <a href="{{ route('category.show', $category->id) }}" class="category-item">
                        <div class="category-icon">
                            @if($category->category_image)
                                <img src="{{ asset('storage/' . $category->category_image) }}" alt="{{ $category->name }}">
                            @else
                                <i class='bx bx-package' style="font-size: 32px;"></i>
                            @endif
                        </div>
                        <span class="category-name">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="daily-discover">
        <h2 class="daily-title">DAILY DISCOVER</h2>

        <div class="products-grid">
            @foreach($products as $product)
                @if($product->stock > 0)
                    <a href="{{ route('product.show', $product->id) }}" class="product-card" data-product-id="{{ $product->id }}">
                        <div class="product-image">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}">
                            @endif
                            <div class="product-tags">
                                @if($product->stock > 0)
                                    <span class="tag">In Stock</span>
                                @endif
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <div class="product-meta">
                                <div class="price-container">
                                    <span class="price">₱{{ number_format($product->price, 2) }}</span>
                                    @if($product->discount_percentage > 0)
                                        <span class="discount-text">-{{ $product->discount_percentage }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="see-more">
            <a href="#" class="see-more-button">See More</a>
        </div>
    </section>

    <script src="{{ asset('js/home.js') }}"></script>
    <script>
        document.querySelectorAll('.categories-row').forEach(row => {
            let isDown = false;
            let startX;
            let scrollLeft;

            row.addEventListener('mousedown', (e) => {
                isDown = true;
                row.style.cursor = 'grabbing';
                startX = e.pageX - row.offsetLeft;
                scrollLeft = row.scrollLeft;
            });

            row.addEventListener('mouseleave', () => {
                isDown = false;
                row.style.cursor = 'grab';
            });

            row.addEventListener('mouseup', () => {
                isDown = false;
                row.style.cursor = 'grab';
            });

            row.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - row.offsetLeft;
                const walk = (x - startX) * 2;
                row.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
</body>
</html>
