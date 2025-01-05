@extends('layouts.seller')

@section('content')
<div class="products-header">
    <div class="products-count">
        <h2>Products Count</h2>
        <span class="badge">Products Count / 3,000</span>
    </div>
    <div class="products-actions">
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
        font-size: 12px; /* Adjust the size as needed */
    }

    .productsTableBody {
        font-size: 12px; /* Adjust the size as needed */
    }
</style>
@endsection
