@extends('layouts.seller')

@section('content')
<div class="tabs">
    <a href="#" class="tab active">Add New Stocks</a>
</div>

<div class="search-filters">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.inventory.stocks.store') }}" method="POST" id="addStockForm">
        @csrf
        <div class="filter-group">
            <div class="filter">
                <label>Product Name</label>
                <select class="filter-input" id="productNameFilter" name="product_id" onchange="loadVariations()" required>
                    <option value="">Choose Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                    @endforeach
                </select>
            </div>

            <div class="filter">
                <label>Variation</label>
                <select class="filter-input" id="variationFilter" name="variation_option_id" onchange="loadSizes()">
                    <option value="">Choose Variation</option>
                    <!-- Variation options will be populated here -->
                </select>
            </div>

            <div class="filter" id="sizeFilterContainer" style="display: none;">
                <label>Size</label>
                <select class="filter-input" id="sizeFilter" name="variation_option_size_id" onchange="updateStock()">
                    <option value="">Choose Size</option>
                    <!-- Size options will be populated here -->
                </select>
            </div>
        </div>

        <div class="filter-group">
            <div class="filter">
                <label>Stock</label>
                <div class="range-input">
                    <input type="text" placeholder="Current Stock" class="filter-input" id="stockMinFilter" readonly>
                    <span>-</span>
                    <input type="number" placeholder="Add New Stocks" class="filter-input" id="stockMaxFilter" name="quantity" required>
                </div>
            </div>

            <div class="filter">
                <label>Sales</label>
                <div class="range-input">
                    <input type="text" placeholder="Min" class="filter-input" id="salesMinFilter" readonly>
                    <span>-</span>
                    <input type="text" placeholder="Max" class="filter-input" id="salesMaxFilter" readonly>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Stock</button>
    </form>
</div>

<div class="products-header">
    <div class="products-count">
        <h2>History</h2>
        <span class="badge" id="productsCountBadge">Products Count / 3,000</span>
    </div>
    <div class="products-actions">
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
                <th>Old Stock</th>
                <th>Quantity Change</th>
                <th>Current Stock ↑</th>
                <th>Date ↑</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            @foreach($stockHistory as $history)
                <tr>
                    <td><input type="checkbox"></td>
                    <td>{{ $history->product->name }}</td>
                    <td>{{ number_format($history->old_stock) }}</td>
                    <td>{{ number_format($history->quantity) }}</td>
                    <td>{{ number_format($history->old_stock + $history->quantity) }}</td>
                    <td>{{ $history->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .products-table {
        font-size: 15px; /* Adjust the size as needed */
    }

    .productsTableBody {
        font-size: 15px; /* Adjust the size as needed */
    }
</style>

<script>
    function loadVariations() {
        const productId = document.getElementById('productNameFilter').value;
        const variationFilter = document.getElementById('variationFilter');
        const sizeFilterContainer = document.getElementById('sizeFilterContainer');
        const stockMinFilter = document.getElementById('stockMinFilter');
        const salesMinFilter = document.getElementById('salesMinFilter');
        const salesMaxFilter = document.getElementById('salesMaxFilter');

        // Clear existing options
        variationFilter.innerHTML = '<option value="">Choose Variation</option>';
        sizeFilterContainer.style.display = 'none';
        stockMinFilter.value = '';
        salesMinFilter.value = '';
        salesMaxFilter.value = '';

        if (productId) {
            // Fetch variation options for the selected product
            fetch(`/admin/inventory/stocks/variations/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(variation => {
                            variation.options.forEach(option => {
                                const opt = document.createElement('option');
                                opt.value = option.id;
                                opt.text = `${variation.name}: ${option.name}`;
                                variationFilter.add(opt);
                            });
                        });
                    }
                });
        }
    }

    function loadSizes() {
        const variationOptionId = document.getElementById('variationFilter').value;
        const sizeFilter = document.getElementById('sizeFilter');
        const sizeFilterContainer = document.getElementById('sizeFilterContainer');
        const stockMinFilter = document.getElementById('stockMinFilter');
        const salesMinFilter = document.getElementById('salesMinFilter');
        const salesMaxFilter = document.getElementById('salesMaxFilter');

        // Clear existing options
        sizeFilter.innerHTML = '<option value="">Choose Size</option>';
        stockMinFilter.value = '';
        salesMinFilter.value = '';
        salesMaxFilter.value = '';

        // Fetch variation sizes for the selected variation option
        fetch(`/admin/inventory/stocks/variations/${variationOptionId}/sizes`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    sizeFilterContainer.style.display = 'block';
                    data.forEach(size => {
                        const opt = document.createElement('option');
                        opt.value = size.id;
                        opt.text = `${size.name}`;
                        sizeFilter.add(opt);
                    });
                } else {
                    sizeFilterContainer.style.display = 'none';
                    // Fetch stock information for the selected variation option
                    fetch(`/admin/inventory/stocks/variations/${variationOptionId}/stock`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                stockMinFilter.value = data.stock;
                                salesMinFilter.value = 'Min Sales'; // Replace with actual min sales value if available
                                salesMaxFilter.value = 'Max Sales'; // Replace with actual max sales value if available
                            }
                        });
                }
            });
    }

    function updateStock() {
        const sizeId = document.getElementById('sizeFilter').value;
        const stockMinFilter = document.getElementById('stockMinFilter');
        const salesMinFilter = document.getElementById('salesMinFilter');
        const salesMaxFilter = document.getElementById('salesMaxFilter');

        // Clear existing values
        stockMinFilter.value = '';
        salesMinFilter.value = '';
        salesMaxFilter.value = '';

        if (sizeId) {
            // Fetch stock information for the selected variation option size
            fetch(`/admin/inventory/stocks/sizes/${sizeId}/stock`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        stockMinFilter.value = data.stock;
                        salesMinFilter.value = 'Min Sales'; // Replace with actual min sales value if available
                        salesMaxFilter.value = 'Max Sales'; // Replace with actual max sales value if available
                    }
                });
        }
    }

    function updateProductsCount(totalProducts) {
        const productsCountBadge = document.getElementById('productsCountBadge');
        const maxProducts = 3000;
        const percentage = (totalProducts / maxProducts) * 100;
        productsCountBadge.textContent = `Products Count: ${totalProducts} / ${maxProducts} (${percentage.toFixed(2)}%)`;
    }

    // Example usage: Update the products count when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const totalProducts = {{ count($products) }}; // Get the total number of products
        updateProductsCount(totalProducts);
    });
</script>
@endsection
