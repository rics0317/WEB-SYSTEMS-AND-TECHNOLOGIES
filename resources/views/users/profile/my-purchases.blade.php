<!-- resources/views/users/profile/my-purchases.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Purchases</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usersprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my-purchases.css') }}">
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content label {
            display: block;
            margin-top: 10px;
        }

        .modal-content input,
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }

        /* Star rating styles */
        .star-rating {
            display: flex;
            align-items: center;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            font-size: 24px;
            padding: 0;
            cursor: pointer;
        }

        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f39c12;
        }
    </style>
</head>
<body>
    @include('users.partials.top-nav')
    @include('users.partials.header')

    <div class="profile-wrapper">
        <div class="container">
            <div class="profile-container">
                <div class="sidebar">
                    <div class="user-info">
                        <img src="{{ Auth::user()->profile_image }}" alt="Profile" class="user-avatar" onerror="this.onerror=null;this.src='{{ asset('images/user.png') }}';">
                        <div>
                            <div class="user-name">{{ Auth::user()->last_name }}, {{ Auth::user()->first_name }}</div>
                            <a href="{{ route('profile.edit') }}" class="edit-link"><i class='bx bx-pencil'></i> Edit Profile</a>
                        </div>
                    </div>
                    <div class="sidebar-menu">
                        <a href="{{ route('profile') }}" class="">My Account</a>
                        <a href="{{ route('profile.change') }}" class="">Change Password</a>
                        <a href="{{ route('my-purchases') }}" class="active">My Purchase</a>
                        <a href="{{ route('notifications.viewAll') }}" class="notification-link">Notifications</a>
                    </div>
                </div>

                <div class="main-content s">
                    <div class="order-status-tabs s">
                        <div class="status-tab s active" data-status="all">All</div>
                        <div class="status-tab s" data-status="pending">Pending</div>
                        <div class="status-tab s" data-status="shipped">Shipped</div>
                        <div class="status-tab s" data-status="delivered">Delivered</div>
                        <div class="status-tab s" data-status="cancelled">Cancelled</div>
                    </div>

                    <div class="search-container s">
                        <i class='bx bx-search'></i>
                        <input type="text"
                               class="search-input s"
                               placeholder="You can search the Order ID or Product name"
                               id="orderSearch">
                    </div>

                    <div class="orders-list s">
                        @if($orders->isEmpty())
                            <p>You have not made any purchases yet.</p>
                        @else
                            @foreach($orders as $order)
                                <div class="order-card s" data-status="{{ strtolower($order->order_status) }}">
                                    @foreach ($order->products as $product)
                                        <div class="product-item s">
                                            <img src="{{ $product['image'] }}"
                                                 alt="{{ $product['product_name'] }}"
                                                 class="product-image s">
                                            <div class="product-details s">
                                                <div class="product-name s">{{ $product['product_name'] }}</div>
                                                @if (!empty($product['variations']))
                                                    <div class="product-variations s">
                                                        @php
                                                            $variationsArray = is_string($product['variations'])
                                                                ? json_decode($product['variations'], true)
                                                                : $product['variations'];
                                                        @endphp
                                                        Variations: {{ implode(', ', $variationsArray) }}
                                                    </div>
                                                @endif
                                                <div class="product-quantity s">
                                                    Quantity: {{ $product['quantity'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="order-total s">
                                        Total: <span class="total-amount s">₱{{ number_format($order->total_price, 2) }}</span>
                                    </div>
                                    @if(strtolower($order->order_status) === 'delivered')
                                        <div class="order-actions s">
                                            @if(isset($ratings[$order->id]))
                                                <div class="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <label>
                                                            <input type="radio" name="rating" value="{{ $i }}" {{ $i <= $ratings[$order->id]->rating ? 'checked' : '' }} disabled>
                                                            <span class="icon">&#9733;</span>
                                                        </label>
                                                    @endfor
                                                </div>
                                            @else
                                                <button class="btn-rate s" data-order-id="{{ $order->id }}">Rate</button>
                                            @endif
                                            <button class="btn-buy-again s" data-order-id="{{ $order->id }}">Buy Again</button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Rate Your Order</h2>
            <form id="ratingForm" method="POST" action="{{ route('rate.order') }}">
                @csrf
                <input type="hidden" name="order_id" id="orderId">
                <div>
                    <label for="rating">Rating (1-5):</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" required>
                </div>
                <div>
                    <label for="comment">Comment:</label>
                    <textarea name="comment" id="comment" rows="4" maxlength="500"></textarea>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/edit-profile.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.status-tab');
            const orders = document.querySelectorAll('.order-card');
            const searchInput = document.getElementById('orderSearch');
            const modal = document.getElementById('ratingModal');
            const closeBtn = document.getElementsByClassName('close')[0];
            const ratingForm = document.getElementById('ratingForm');
            const orderIdInput = document.getElementById('orderId');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    const status = tab.getAttribute('data-status');

                    orders.forEach(order => {
                        if (status === 'all' || order.getAttribute('data-status') === status) {
                            order.style.display = 'block';
                        } else {
                            order.style.display = 'none';
                        }
                    });
                });
            });

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                orders.forEach(order => {
                    const productNames = order.querySelectorAll('.product-name');
                    let found = false;

                    productNames.forEach(name => {
                        if (name.textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                    });

                    order.style.display = found ? 'block' : 'none';
                });
            });

            document.querySelectorAll('.btn-rate').forEach(button => {
                button.addEventListener('click', () => {
                    const orderId = button.getAttribute('data-order-id');
                    orderIdInput.value = orderId;
                    modal.style.display = 'block';
                });
            });

            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });

            document.querySelectorAll('.btn-buy-again').forEach(button => {
                button.addEventListener('click', () => {
                    const orderId = button.getAttribute('data-order-id');
                    window.location.href = `/buy-again/${orderId}`;
                });
            });

            ratingForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(ratingForm);
                const orderId = formData.get('order_id');

                fetch(ratingForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the UI to show the rating stars
                        const orderCard = document.querySelector(`.order-card[data-status="delivered"][data-order-id="${orderId}"]`);
                        const rating = formData.get('rating');
                        const starRating = document.createElement('div');
                        starRating.classList.add('star-rating');

                        for (let i = 1; i <= 5; i++) {
                            const label = document.createElement('label');
                            const input = document.createElement('input');
                            input.type = 'radio';
                            input.name = 'rating';
                            input.value = i;
                            input.disabled = true;
                            if (i <= rating) {
                                input.checked = true;
                            }
                            label.appendChild(input);
                            label.innerHTML += '&#9733;';
                            starRating.appendChild(label);
                        }

                        orderCard.querySelector('.order-actions').innerHTML = '';
                        orderCard.querySelector('.order-actions').appendChild(starRating);
                        orderCard.querySelector('.order-actions').appendChild(orderCard.querySelector('.btn-buy-again'));

                        modal.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</body>
</html>
