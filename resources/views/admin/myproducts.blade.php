@extends('layouts.seller')

@section('content')
    <div class="tabs">
        <a href="#" class="tab active">All</a>
        <a href="#" class="tab">Sold out</a>
    </div>

    <div class="search-filters">
        <div class="filter-group">
            <div class="filter">
                <label>Product Name</label>
                <input type="text" placeholder="Input" class="filter-input" id="productNameFilter">
            </div>

            <div class="filter">
                <label>Category</label>
                <select class="filter-input" id="categoryFilter">
                    <option value="">Choose Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter">
                <label>Variation</label>
                <select class="filter-input" id="variationFilter">
                    <option value="">Choose Variation</option>
                    @foreach($variations as $variation)
                        <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="filter-group">
            <div class="filter">
                <label>Stock</label>
                <div class="range-input">
                    <input type="text" placeholder="Min" class="filter-input" id="stockMinFilter">
                    <span>-</span>
                    <input type="text" placeholder="Max" class="filter-input" id="stockMaxFilter">
                </div>
            </div>

            <div class="filter">
                <label>Sales</label>
                <div class="range-input">
                    <input type="text" placeholder="Min" class="filter-input" id="salesMinFilter">
                    <span>-</span>
                    <input type="text" placeholder="Max" class="filter-input" id="salesMaxFilter">
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <button class="search-button" id="searchButton">Search</button>
            <button class="reset-button" id="resetButton">Reset</button>
        </div>
    </div>

    <div class="products-header">
        <div class="products-count">
            <h2>{{ $products->count() }} Products</h2>
            <span class="badge">{{ $products->count() }} / 3,000</span>
        </div>
        <div class="products-actions">
            <a href="{{ route('admin.products.add-product-step1') }}" class="add-product-button no-underline">
                <i class='bx bx-plus'></i>
                Add a New Product
            </a>
            <button class="batch-button">
                Batch Tools
                <i class='bx bx-chevron-down'></i>
            </button>
            <button class="view-button">
                <i class='bx bx-list-ul'></i>
            </button>
            <button class="grid-button">
                <i class='bx bx-grid-alt'></i>
            </button>
        </div>
    </div>

    <div class="products-table">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Product Name</th>
                    <th>SKU</th>
                    <th>Variations</th>
                    <th>Price ↑</th>
                    <th>Stock ↑</th>
                    <th>Sales ↑</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                @foreach($products as $product)
                    <tr data-category="{{ $product->category }}" data-stock="{{ $product->stock }}" data-sales="{{ $product->sales }}">
                        <td><input type="checkbox"></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>
                            @foreach($product->variations as $variation)
                                <ul>
                                    @foreach($variation->options as $option)
                                        <li>{{ $option->name }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->sales }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit-product', $product->id) }}" class="edit-button btn-orange">Edit</a>
                            <form action="{{ route('seller.delete-product', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button btn-red" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .products-table {
            font-size: 12px; /* Adjust the size as needed */
        }

        .productsTableBody {
            font-size: 12px; /* Adjust the size as needed */
        }
    </style>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.getElementById('searchButton');
        const resetButton = document.getElementById('resetButton');
        const productNameFilter = document.getElementById('productNameFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const variationFilter = document.getElementById('variationFilter');
        const stockMinFilter = document.getElementById('stockMinFilter');
        const stockMaxFilter = document.getElementById('stockMaxFilter');
        const salesMinFilter = document.getElementById('salesMinFilter');
        const salesMaxFilter = document.getElementById('salesMaxFilter');

        searchButton.addEventListener('click', function() {
            const params = {
                productName: productNameFilter.value,
                category: categoryFilter.value,
                variation: variationFilter.value,
                stockMin: stockMinFilter.value,
                stockMax: stockMaxFilter.value,
                salesMin: salesMinFilter.value,
                salesMax: salesMaxFilter.value,
            };

            const queryString = Object.keys(params)
                .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
                .join('&');

            window.location.href = `{{ route('admin.myproducts') }}?${queryString}`;
        });

        resetButton.addEventListener('click', function() {
            productNameFilter.value = '';
            categoryFilter.value = '';
            variationFilter.value = '';
            stockMinFilter.value = '';
            stockMaxFilter.value = '';
            salesMinFilter.value = '';
            salesMaxFilter.value = '';
            window.location.href = `{{ route('admin.myproducts') }}`;
        });
    });
</script>
