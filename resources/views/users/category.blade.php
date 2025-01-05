<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Category Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/category.css') }}" rel="stylesheet">
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <section class="daily-discover">
        <div class="shopee-mall">
            <div class="shopee-mall-header">
                <h3>BRANDS</h3>
                <a href="#" class="see-all">See All <i class='bx bx-chevron-right'></i></a>
            </div>
            <div class="brand-logos">
                @foreach($brands as $brand)
                    <div class="brand-logo">
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="main-container">
            <!-- Left Sidebar with Categories -->
            <div class="sidebar" style="margin-top: 60px;">
                <div class="sidebar-header">
                    <i class='bx bx-menu'></i>
                    All Categories
                </div>
                <div class="sidebar-menu">
                    @foreach($category->subCategories as $subCategory)
                        <a href="#"
                           class="sidebar-item @if(request()->get('subcategory') == $subCategory->id) active @endif"
                           data-subcategory-id="{{ $subCategory->id }}">
                            {{ $subCategory->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Products Section -->
            <div class="products-section" style="margin-top: 60px;">
                <!-- Sort Options Container -->
                <div class="sort-options-container">
                    <div class="sort-options">
                        <span>Sort by:</span>
                        <div class="sort-buttons">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                               class="sort-btn @if(request()->get('sort') == 'latest' || !request()->has('sort')) active @endif">Latest</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'top_sales']) }}"
                               class="sort-btn @if(request()->get('sort') == 'top_sales') active @endif">Top Sales</a>
                            <div class="price-dropdown">
                                <button class="sort-btn">Price <i class='bx bx-chevron-down'></i></button>
                                <div class="dropdown-content">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Low to High</a>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">High to Low</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="products-grid" id="products-grid">
                    @foreach($category->products as $product)
                        <a href="{{ route('product.show', $product->id) }}" class="product-card" data-product-id="{{ $product->id }}" data-subcategory-id="{{ $product->sub_category_id }}">
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
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/home.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            const productsGrid = document.getElementById('products-grid');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    const subcategoryId = this.getAttribute('data-subcategory-id');

                    // Remove active class from all sidebar items
                    sidebarItems.forEach(item => item.classList.remove('active'));
                    // Add active class to the clicked item
                    this.classList.add('active');

                    // Filter products based on the selected subcategory
                    const productCards = productsGrid.querySelectorAll('.product-card');
                    productCards.forEach(card => {
                        if (card.getAttribute('data-subcategory-id') === subcategoryId || subcategoryId === null) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
