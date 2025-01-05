<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="container">
        @if($cartItems->isEmpty())
            <div class="empty-cart">
                <p>Your cart is empty.</p>
            </div>
        @else
            <div class="cart1-header">
                <input type="checkbox" id="select-all-header" class="select-all" />
                <div>Product</div>
                <div>Unit Price</div>
                <div>Quantity</div>
                <div>Total</div>
                <div>Actions</div>
            </div>

            @foreach($cartItems as $item)
                @php
                    $stock = $item->product->stock;
                    $variations = json_decode($item->variations, true);
                    $variationTexts = [];

                    if (!empty($variations)) {
                        foreach ($variations as $value) {
                            $variationTexts[] = $value;
                        }

                        // Check if the product has variations and sizes
                        if (isset($item->variation_option_id) && isset($item->variation_option_size_id)) {
                            $variationOptionSize = \App\Models\VariationOptionSize::find($item->variation_option_size_id);
                            if ($variationOptionSize) {
                                $stock = $variationOptionSize->stock;
                            }
                        } elseif (isset($item->variation_option_id)) {
                            $variationOption = \App\Models\VariationOption::find($item->variation_option_id);
                            if ($variationOption) {
                                $stock = $variationOption->stock;
                            }
                        }
                    }
                @endphp

                <div class="cart1-item">
                    <input type="checkbox" class="item-checkbox" data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}" data-id="{{ $item->id }}" />
                    <div class="product-info">
                        <img src="{{ $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" style="width: 90px; height: 90px;">
                        <div>
                            <h3 title="{{ $item->product->name }}">{{ Str::limit($item->product->name, 30, '...') }}</h3>
                            <p>Variations: {{ implode(', ', $variationTexts) }}</p>
                        </div>
                    </div>
                    <div class="unit-price">₱{{ number_format($item->product->price, 2) }}</div>
                    <div class="quantity-controls">
                        <div class="input-group">
                            <button class="quantity-btn decrease-btn" data-stock="{{ $stock }}">-</button>
                            <input type="number" value="{{ $item->quantity }}" class="quantity-input" min="1" max="{{ $stock }}" readonly>
                            <button class="quantity-btn increase-btn" data-stock="{{ $stock }}">+</button>
                        </div>
                        <div class="stock-info {{ $stock <= 5 ? 'stock-low' : '' }}">
                            {{ $stock }} items left
                        </div>
                    </div>
                    <div class="item-total">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                    <div>
                        <form action="{{ route('users.cart.remove', $item->id) }}" method="POST" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn">
                                <i class='bx bx-trash'></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="cart-footer">
                <div>
                    <input type="checkbox" id="select-all-footer" class="select-all" /> Select All
                </div>
                <div>
                    <span>Total (<span id="selected-count">0</span> items): </span>
                    <span style="color: #ee4d2d; font-size: 20px; font-weight: 500;" id="total-price">₱0.00</span>
                    <button class="checkout-btn">Check Out</button>
                </div>
            </div>
        @endif
    </div>
    <script src="{{ asset('js/home.js') }}"></script>

    <script>
        function updateItemCheckboxData(checkbox) {
            const quantityInput = checkbox.closest('.cart1-item').querySelector('.quantity-input');
            checkbox.setAttribute('data-quantity', quantityInput.value);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckboxes = document.querySelectorAll('.select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const totalPriceElement = document.getElementById('total-price');
            const selectedCountElement = document.getElementById('selected-count');
            const checkoutButton = document.querySelector('.checkout-btn');

            function updateTotalPrice() {
                let totalPrice = 0;
                let selectedCount = 0;

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        updateItemCheckboxData(checkbox);
                        const price = parseFloat(checkbox.getAttribute('data-price'));
                        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                        totalPrice += price * quantity;
                        selectedCount++;
                    }
                });

                totalPriceElement.textContent = `₱${totalPrice.toFixed(2)}`;
                selectedCountElement.textContent = selectedCount;
            }

            function updateSelectAllState() {
                const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
                selectAllCheckboxes.forEach(checkbox => {
                    checkbox.checked = allChecked;
                });
            }

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateTotalPrice();
                    updateSelectAllState();
                });
            });

            selectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(itemCheckbox => {
                        itemCheckbox.checked = checkbox.checked;
                    });
                    updateTotalPrice();
                });
            });

            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const quantityInput = this.parentElement.querySelector('.quantity-input');
                    const currentValue = parseInt(quantityInput.value);
                    const price = parseFloat(this.closest('.cart1-item').querySelector('.item-checkbox').getAttribute('data-price'));
                    const itemTotalElement = this.closest('.cart1-item').querySelector('.item-total');
                    const stock = parseInt(this.getAttribute('data-stock'));

                    if (this.classList.contains('increase-btn') && currentValue < stock) {
                        quantityInput.value = currentValue + 1;
                    } else if (this.classList.contains('decrease-btn') && currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }

                    const newQuantity = parseInt(quantityInput.value);
                    itemTotalElement.textContent = `₱${(price * newQuantity).toFixed(2)}`;

                    // Update the checkbox data-quantity attribute
                    const checkbox = this.closest('.cart1-item').querySelector('.item-checkbox');
                    checkbox.setAttribute('data-quantity', newQuantity);

                    updateTotalPrice();

                    // Send AJAX request to update the cart item quantity
                    const cartItemId = checkbox.getAttribute('data-id');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/users/cart/update/${cartItemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ quantity: newQuantity })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Cart item quantity updated successfully!',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });

            checkoutButton.addEventListener('click', function() {
                const selectedItems = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked);

                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select at least one product to checkout!',
                    });
                } else {
                    @auth
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, checkout!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const selectedItemIds = selectedItems.map(item => item.getAttribute('data-id'));
                                window.location.href = `{{ url('users/cart/checkout') }}?items=${selectedItemIds.join(',')}`;
                            }
                        });
                    @else
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'You need to log in to proceed to checkout!',
                            confirmButtonText: 'Login',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `{{ route('login') }}`;
                            }
                        });
                    @endauth
                }
            });
        });
    </script>
</body>
</html>
