@extends('layouts.seller')

@section('content')
<div class="container1">
    <div class="orders-wrapper1">
        <h1 class="page-title1">All Orders</h1>

        <!-- Search Bar -->
        <form action="{{ route('admin.orders.all') }}" method="GET" class="search-form1">
            <div class="search-group1">
                <input type="text" name="search" class="search-input1" placeholder="Search by Order ID or Customer Name" value="{{ request('search') }}">
                <button class="search-button1" type="submit">Search</button>
            </div>
        </form>

        <!-- Filters -->
        <form action="{{ route('admin.orders.all') }}" method="GET" class="filters-form1" id="filtersForm1">
            <div class="filters-grid1">
                <select name="order_status" class="filter-select1">
                    <option value="">All Order Statuses</option>
                    @foreach(App\Http\Controllers\Admin\OrdersController::getValidOrderStatuses() as $value => $label)
                        <option value="{{ $value }}" {{ request('order_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="payment_status" class="filter-select1">
                    <option value="">All Payment Statuses</option>
                    @foreach(App\Http\Controllers\Admin\OrdersController::getValidPaymentStatuses() as $value => $label)
                        <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="filter-button1">Apply Filters</button>
        </form>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert1 alert-success1">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert1 alert-danger1">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive1">
            <table class="orders-table1">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr data-order-id="{{ $order->id }}">
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->full_name }}</td>
                            <td>{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="status-badge1 payment-{{ strtolower($order->payment_status) }}1">
                                    {{ App\Http\Controllers\Admin\OrdersController::getValidPaymentStatuses()[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge1 order-{{ strtolower($order->order_status) }}1 order-status1">
                                    {{ App\Http\Controllers\Admin\OrdersController::getValidOrderStatuses()[$order->order_status] ?? $order->order_status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.view', $order->id) }}" class="action-button1">View</a>
                                <button
                                    class="action-button1 edit-button1"
                                    onclick="openEditModal('{{ $order->id }}', '{{ $order->order_status }}')"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper1">
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal1" class="modal1">
    <div class="modal-content1">
        <div class="modal-header1">
            <h2>Update Order Status</h2>
            <span class="close1">&times;</span>
        </div>
        <div class="modal-body1">
            <form id="updateOrderForm1" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group1">
                    <label for="order_status">Order Status:</label>
                    <select name="order_status" id="order_status1" class="form-control1">
                        @foreach(App\Http\Controllers\Admin\OrdersController::getValidOrderStatuses() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer1">
                    <button type="button" class="btn1 btn-secondary1" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn1 btn-primary1">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Existing styles */
/* ... (keep your existing styles for other elements) ... */

/* Modal Styles */
.modal1 {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.8s ease;
}

.modal-content1 {
    background-color: #fff;
    margin: 10% auto;
    max-width: 500px;
    border-radius: 0px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    animation: slideIn 0.3s ease;
}

.modal-header1 {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header1 h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #333;
}

.modal-body1 {
    padding: 20px;
}

.modal-footer1 {
    padding: 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.close1 {
    font-size: 24px;
    font-weight: bold;
    color: #666;
    cursor: pointer;
    transition: color 0.2s;
}

.close1:hover {
    color: #333;
}

.form-group1 {
    margin-bottom: 20px;
}

.form-group1 label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

.form-control1 {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 0px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-control1:focus {
    border-color: #4a90e2;
    outline: none;
}

.btn1 {
    padding: 10px 20px;
    border-radius: 0px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary1 {
    background-color:rgb(252, 71, 0);
    color: white;
    border: none;
}

.btn-primary1:hover {
    background-color:rgb(255, 30, 0);
}

.btn-secondary1 {
    background-color: #f5f5f5;
    color: #333;
    border: 1px solid #ddd;
}

.btn-secondary1:hover {
    background-color: #e5e5e5;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<script>
let currentOrderId = null;

function closeModal() {
    document.getElementById('editModal1').style.display = 'none';
}

function openEditModal(orderId, currentOrderStatus) {
    currentOrderId = orderId;
    const modal = document.getElementById('editModal1');
    const orderStatusSelect = document.getElementById('order_status1');

    // Set current status
    orderStatusSelect.value = currentOrderStatus;

    // Update form action
    const form = document.getElementById('updateOrderForm1');
    form.action = `/admin/orders/${orderId}`;

    modal.style.display = 'block';
}

// Close modal when clicking the X
document.querySelector('.close1').onclick = closeModal;

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal1');
    if (event.target == modal) {
        closeModal();
    }
}

// Handle form submission
document.getElementById('updateOrderForm1').onsubmit = function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the status in the table
            const row = document.querySelector(`tr[data-order-id="${currentOrderId}"]`);
            const orderStatusCell = row.querySelector('.order-status1');

            if (orderStatusCell) {
                orderStatusCell.textContent = formData.get('order_status');
                orderStatusCell.className = `status-badge1 order-${formData.get('order_status')}1 order-status1`;
            }

            // Close modal
            closeModal();

            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert1 alert-success1';
            alertDiv.textContent = data.message;
            document.querySelector('.orders-wrapper1').insertBefore(alertDiv, document.querySelector('.table-responsive1'));

            // Remove alert after 3 seconds
            setTimeout(() => alertDiv.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert1 alert-danger1';
        alertDiv.textContent = 'Error updating order status';
        document.querySelector('.orders-wrapper1').insertBefore(alertDiv, document.querySelector('.table-responsive1'));
        setTimeout(() => alertDiv.remove(), 3000);
    });
};
</script>

@endsection
