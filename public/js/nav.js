document.addEventListener('DOMContentLoaded', function() {
    const notificationLink = document.querySelector('.notification-link');
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationList = document.querySelector('#notification-list');
    const notificationCount = document.querySelector('#notification-count');
    const notificationSound = document.getElementById('notification-sound');
    let lastNotificationCount = 0; // Track the last notification count

    // Fetch notifications and update the count when the page loads
    fetchNotifications();

    notificationLink.addEventListener('click', function() {
        notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
        fetchNotifications();
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
                if (unreadCount > lastNotificationCount) {
                    playNotificationSound();
                    Swal.fire({
                        title: 'New Notification!',
                        text: 'You have new notifications.',
                        icon: 'info',
                        confirmButtonText: 'Okay'
                    });
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
                fetchNotifications();
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
});
