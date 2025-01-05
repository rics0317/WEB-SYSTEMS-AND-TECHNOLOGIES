<!DOCTYPE html>
<html>
<head>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header class="main-header">
        <a href="{{ route('users.home') }}" class="logo">
            <img src="{{ asset('images/shopee.png') }}" alt="UserPanel Logo" style="height: 80px;">
        </a>
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Sign up and get 100% off on your first order" id="search-bar">
            <button class="search-button"><i class='bx bx-search'></i></button>
            <div class="search-results" id="search-results" style="display: none;">
                <ul class="search-results-list">
                </ul>
            </div>
        </div>
        <div class="cart-container">
            <a id="cart-icon" class="cart-icon">
                <i class='bx bx-cart'></i>
                <span class="cart-count" id="cart-count">{{ $cartItems->count() }}</span>
            </a>
            <div class="cart-dropdown" id="cart-dropdown">
                <h3 class="recently-added-products">Recently Added Products</h3>
                <ul class="cart-items" id="cart-items">
                    @foreach($cartItems as $item)
                        <li class="cart-item">
                            @if($item->product->images->isNotEmpty())
                            <img src="{{ $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="cart-item-image">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" alt="Placeholder Image" class="cart-item-image">
                            @endif
                            <div class="item-details">
                                <span class="item-name">{{ Str::limit($item->product->name, 20) }}</span>
                                <span class="item-price">₱{{ number_format($item->product->price, 2) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="cart-summary">
                    <p>{{ $cartItems->count() }} More Products In Cart</p>
                    <a href="{{ route('users.cart') }}" class="view-cart-btn">View My Shopping Cart</a>
                </div>
            </div>
        </div>
    </header>

    <style>

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 4px;
        }

        .search-results-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .search-result-item:hover {
            background-color: #f5f5f5;
        }

        .search-result-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 12px;
        }


        .search-result-name {
            font-size: 14px;
            color: #333;
        }

    </style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.getElementById('search-bar');
            const searchResults = document.getElementById('search-results');
            const searchResultsList = searchResults.querySelector('.search-results-list');
            const cartIcon = document.getElementById('cart-icon');
            const cartDropdown = document.getElementById('cart-dropdown');

            searchBar.addEventListener('input', function() {
                const query = this.value.trim();
        
                if (query.length === 0) {
                    searchResults.style.display = 'none';
                    return;
                }

                fetch(`/search-products?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(products => {
                        searchResultsList.innerHTML = '';
                
                        if (products.length > 0) {
                            products.forEach(product => {
                                const li = document.createElement('li');
                                li.className = 'search-result-item';
                                
                                let imagePath = product.images && product.images.length > 0
                                    ? `/storage/${product.images[0].image_path}`
                                    : '/images/placeholder.png';

                                li.innerHTML = `
                                    <img src="${imagePath}" alt="${product.name}" class="search-result-image">
                                    <span class="search-result-name">${product.name}</span>
                                `;
                        
                                li.addEventListener('click', () => {
                                    window.location.href = `{{ route('product.show', '') }}/${product.id}`;
                                });
                        
                                searchResultsList.appendChild(li);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                    });
            });

            // Cart dropdown functionality
            cartIcon.addEventListener('mouseenter', function() {
                cartDropdown.style.display = 'block';
            });

            cartIcon.addEventListener('mouseleave', function(e) {
                setTimeout(() => {
                    if (!cartDropdown.matches(':hover')) {
                        cartDropdown.style.display = 'none';
                    }
                }, 200);
            });

            cartDropdown.addEventListener('mouseleave', function() {
                cartDropdown.style.display = 'none';
            });

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchBar.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

