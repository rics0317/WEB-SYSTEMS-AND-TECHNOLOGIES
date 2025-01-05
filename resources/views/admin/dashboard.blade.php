@extends('layouts.seller')

@section('content')
    <section class="dashboard-section">
        <h2>To Do List</h2>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>To-Process Shipment</h3>
                <div class="value">0</div>
            </div>
            <div class="dashboard-card">
                <h3>Processed Shipment</h3>
                <div class="value">0</div>
            </div>
            <div class="dashboard-card">
                <h3>Pending Return/Refund</h3>
                <div class="value">0</div>
            </div>
            <div class="dashboard-card">
                <h3>Pending Cancellation</h3>
                <div class="value">0</div>
            </div>
            <div class="dashboard-card">
                <h3>Sold Out Products</h3>
                <div class="value">0</div>
            </div>
        </div>
    </section>

    <section class="dashboard-section">
        <h2>Business Insights</h2>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Visitors</h3>
                <div class="value">0</div>
                <div class="trend up">+800.00% vs yesterday</div>
            </div>
            <div class="dashboard-card">
                <h3>Page Views</h3>
                <div class="value">0</div>
                <div class="trend up">+42.86% vs yesterday</div>
            </div>
            <div class="dashboard-card">
                <h3>Orders</h3>
                <div class="value">0</div>
                <div class="trend down">-100.00% vs yesterday</div>
            </div>
            <div class="dashboard-card">
                <h3>Conversion Rate</h3>
                <div class="value">0.00%</div>
                <div class="trend down">-100.00% vs yesterday</div>
            </div>
        </div>
    </section>
@endsection
