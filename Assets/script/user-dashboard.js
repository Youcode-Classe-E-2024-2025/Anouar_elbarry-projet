// User Dashboard Interactivity
document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Navigation Highlighting
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(l => l.classList.remove('bg-blue-50', 'text-blue-600'));
            link.classList.add('bg-blue-50', 'text-blue-600');
        });
    });

    // Notification Interaction
    const notificationIcon = document.querySelector('.fa-bell');
    const notificationBadge = notificationIcon.nextElementSibling;
    notificationIcon.addEventListener('click', () => {
        // Toggle notification dropdown or view
        alert('Vous avez 3 nouvelles notifications');
        notificationBadge.classList.add('hidden');
    });

    // Search Functionality (Basic)
    const searchInput = document.querySelector('input[placeholder="Rechercher..."]');
    searchInput.addEventListener('keyup', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const taskItems = document.querySelectorAll('#taskList li');
        
        taskItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Logout Functionality
    const logoutButton = document.querySelector('a[href="#"]:last-child');
    logoutButton.addEventListener('click', (e) => {
        e.preventDefault();
        if(confirm('Voulez-vous vraiment vous déconnecter ?')) {
            // Implement logout logic
            window.location.href = 'login.html';
        }
    });
});
