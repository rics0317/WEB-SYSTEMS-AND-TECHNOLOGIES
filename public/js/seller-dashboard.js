document.addEventListener('DOMContentLoaded', function() {
    const submenuTriggers = document.querySelectorAll('.sidebar-menu a.has-submenu');

    submenuTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('sidebar-submenu')) {
                submenu.classList.toggle('active');
                this.setAttribute('aria-expanded', submenu.classList.contains('active'));
            }
        });
    });
});
