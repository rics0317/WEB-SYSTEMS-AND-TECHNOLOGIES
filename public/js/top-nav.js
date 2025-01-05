document.addEventListener('DOMContentLoaded', function() {
    const notificationLink = document.querySelector('.notification-link');
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationList = document.querySelector('#notification-list');
    const notificationCount = document.querySelector('#notification-count');
    const notificationSound = document.getElementById('notification-sound');
    let lastNotificationCount = 0; // Track the last notification count

    // Fetch notifications and update the count periodically
    fetchNotifications(); // Initial fetch
    setInterval(fetchNotifications, 3000); // Check every 3 seconds

    notificationLink.addEventListener('click', function() {
        notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
    });

    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                notificationList.innerHTML = '';
                let unreadCount = 0;

                data.forEach(notification => {
                    const notificationItem = document.createElement('li');
                    notificationItem.classList.add('notification-item');

                    if (notification.read) {
                        notificationItem.classList.add('read');
                    } else {
                        unreadCount++;
                    }

                    notificationItem.innerHTML = `<p>${notification.message}</p>`;
                    notificationItem.addEventListener('click', function() {
                        markAsRead(notification.id);
                    });

                    notificationList.appendChild(notificationItem);
                });

                updateNotificationCount(unreadCount);

                // Play sound if new unread notifications are detected
                if (unreadCount > lastNotificationCount) {
                    playNotificationSound();
                }

                lastNotificationCount = unreadCount; // Update the last notification count
            });
    }

    function markAsRead(id) {
        fetch(`/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fetchNotifications(); // Re-fetch immediately after marking as read
            }
        });
    }

    function updateNotificationCount(count) {
        if (count > 0) {
            notificationCount.textContent = count;
            notificationCount.style.display = 'inline';
        } else {
            notificationCount.style.display = 'none';
        }
    }

    function playNotificationSound() {
        notificationSound.play();
    }

    // Display success and error messages with SweetAlert
    const successMessage = document.querySelector('.success-message');
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: successMessage.textContent,
        });
    }

    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage.textContent,
        });
    }
});
