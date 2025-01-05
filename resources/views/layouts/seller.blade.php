<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Shopee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-orders.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('images/s.png') }}" type="image/png">
    <style>
        .notification-bell {
            position: relative;
            display: inline-block;
        }

        .notification-count {
            position: absolute;
            top: -10px;
            right: -13px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .notification-dropdown .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .notification-dropdown .notification-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="{{ route('users.home') }}" class="logo">
                <i class='bx bxs-shopping-bag'></i>
                <span>Shopee</span>
            </a>
            <h1 class="header-title">Admin Dashboard</h1>
        </div>
        <div class="header-right">
            <div class="notification-bell" id="notificationBell">
                <i class='bx bx-bell'></i>
                <span class="notification-count" id="notificationCount">0</span>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-item" id="newOrdersCount">New order received</div>
                    <div class="notification-item" id="lowStockProductsCount">Product stock low</div>
                    <div class="notification-item" id="newUsersCount">New user registered</div>
                </div>
            </div>

            @if(auth()->user() && auth()->user()->role->role_name === 'admin')
            <div class="user-profile">
                <img src="{{ asset('' . auth()->user()->profile_image) }}" alt="User Avatar">
                <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <div class="dropdown">
                    <a href="{{ route('admin.profile.change-prof') }}">Profile</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
            @endif
        </div>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="active"><i class='bx bxs-dashboard'></i> Dashboard</a>

                <!-- User Management -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-user'></i>User Management</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('admin.manage.user-management') }}">User List</a>
                </div>

                <!-- Products -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-package'></i>Products</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('admin.myproducts') }}">My Products</a>
                    <a href="{{ route('admin.products.add-product-step1') }}">Add New Products</a>
                    <a href="{{ route('admin.products.add-newbrand') }}">Add Brand</a>
                </div>

                <!-- Orders -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-cart'></i>Orders</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('admin.orders.all') }}">Orders Listing</a>
                    <a href="">Canceled Orders</a>
                    <a href="{{ route('admin.orders.history') }}">Order History</a>
                </div>

                <!-- Customers -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-user'></i>Sales</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="">Sales Reports</a>
                    <a href="">Revenue</a>
                    <a href="">Top Selling</a>
                </div>

                <!-- Categories -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-category'></i>Categories</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('admin.products.categories.add') }}">Add Category</a>
                    <a href="{{ route('admin.products.subcategories.add') }}">Add SubCategory</a>
                    <a href="{{ route('admin.products.itemcategories.add') }}">Add ItemCategory</a>
                </div>

                <!-- Stock Management -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-box'></i>Inventory </span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('admin.inventory.stocks.add') }}">Add Stocks</a>
                </div>

                <!-- Settings -->
                <a href="#" class="has-submenu">
                    <span><i class='bx bx-cog'></i>Settings</span>
                    <i class='bx bx-chevron-down submenu-icon'></i>
                </a>
                <div class="sidebar-submenu">
                    <a href="{{ route('banners.index') }}">Banner Settings</a>
                    <a href="#">General Settings</a>
                    <a href="#">Notification Settings</a>
                </div>
            </nav>
        </aside>

        <main class="product-management">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/seller-dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all menu items with submenu
            const menuWithSubmenu = document.querySelectorAll('.has-submenu');

            // Select the sidebar submenu elements
            const submenus = document.querySelectorAll('.sidebar-submenu');

            // Toggle submenu when parent menu is clicked
            menuWithSubmenu.forEach((menuItem, index) => {
                menuItem.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Toggle active class on the parent menu item
                    this.classList.toggle('active');

                    // Toggle visibility of the corresponding submenu
                    submenus[index].classList.toggle('show');

                    // Rotate the dropdown icon
                    const dropdownIcon = this.querySelector('.submenu-icon');
                    if (dropdownIcon) {
                        dropdownIcon.classList.toggle('rotated');
                    }
                });
            });

            // Highlight current page in sidebar
            function highlightCurrentPage() {
                const currentPath = window.location.pathname;
                const sidebarLinks = document.querySelectorAll('.sidebar-menu a');

                sidebarLinks.forEach(link => {
                    // Remove active class from all links first
                    link.classList.remove('active');

                    // Check if link href matches current path
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');

                        // If the active link is in a submenu, open that submenu
                        const parentSubmenu = link.closest('.sidebar-submenu');
                        if (parentSubmenu) {
                            const parentMenuItem = parentSubmenu.previousElementSibling;
                            if (parentMenuItem) {
                                parentMenuItem.classList.add('active');
                                parentSubmenu.classList.add('show');
                            }
                        }
                    }
                });
            }

            // Call the function to highlight current page on load
            highlightCurrentPage();

            // Notification bell functionality
            const notificationBell = document.getElementById('notificationBell');
            const notificationDropdown = document.getElementById('notificationDropdown');

            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function() {
                notificationDropdown.style.display = 'none';
            });

            notificationDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Function to update notification counts
            function updateNotificationCounts() {
                $.ajax({
                    url: '{{ route('admin.getNotificationCounts') }}',
                    method: 'GET',
                    success: function(data) {
                        const totalCount = data.newOrdersCount + data.lowStockProductsCount + data.newUsersCount;
                        $('#notificationCount').text(totalCount);

                        $('#newOrdersCount').text(`New orders: ${data.newOrdersCount}`);
                        $('#lowStockProductsCount').text(`Low stock products: ${data.lowStockProductsCount}`);
                        $('#newUsersCount').text(`New users: ${data.newUsersCount}`);
                    }
                });
            }

            // Initial call to update notification counts
            updateNotificationCounts();

            // Periodically update notification counts every 5 minutes
            setInterval(updateNotificationCounts, 300000);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>
