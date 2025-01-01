// Project Manager Dashboard Interactivity
document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Navigation Highlighting
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.forEach(l => l.classList.remove('bg-blue-50', 'text-blue-600'));
            link.classList.add('bg-blue-50', 'text-blue-600');
        });
    });

    // New Project Modal Trigger
    const newProjectButton = document.querySelector('button[class*="bg-blue-500"]');
    newProjectButton.addEventListener('click', () => {
        // Open new project creation modal
        const modal = createProjectModal();
        document.body.appendChild(modal);
    });

    // Add Team Member Modal Trigger
    const addMemberButton = document.querySelector('button[class*="fa-user-plus"]');
    addMemberButton.addEventListener('click', () => {
        // Open add team member modal
        const modal = createTeamMemberModal();
        document.body.appendChild(modal);
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

    // Modal Creation Functions
    function createProjectModal() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl p-8 w-96">
                <h2 class="text-2xl font-bold mb-6">Créer un Nouveau Projet</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Nom du Projet</label>
                        <input type="text" class="w-full px-3 py-2 border rounded-lg" placeholder="Entrez le nom du projet">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg" rows="3" placeholder="Décrivez le projet"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Créer</button>
                    </div>
                </form>
            </div>
        `;

        // Close modal functionality
        modal.querySelector('button[type="button"]').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        return modal;
    }

    function createTeamMemberModal() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl p-8 w-96">
                <h2 class="text-2xl font-bold mb-6">Ajouter un Membre</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Nom Complet</label>
                        <input type="text" class="w-full px-3 py-2 border rounded-lg" placeholder="Nom et prénom">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-3 py-2 border rounded-lg" placeholder="Email du membre">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Rôle</label>
                        <select class="w-full px-3 py-2 border rounded-lg">
                            <option>Développeur</option>
                            <option>Designer</option>
                            <option>Chef de Projet</option>
                            <option>Testeur</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Ajouter</button>
                    </div>
                </form>
            </div>
        `;

        // Close modal functionality
        modal.querySelector('button[type="button"]').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        return modal;
    }
});
