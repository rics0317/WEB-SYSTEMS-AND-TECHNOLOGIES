document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.createElement('button');
    toggleButton.textContent = 'Toggle Sidebar';
    toggleButton.style.margin = '20px';

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    document.body.insertBefore(toggleButton, document.body.firstChild);
});
