<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Chef de Projet</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white shadow-xl p-6">
            <div class="mb-10 text-center">
                <img src="https://via.placeholder.com/150/0000FF/808080?text=Logo" alt="Logo" class="mx-auto w-24 h-24 rounded-full object-cover">
                <h2 class="mt-4 text-xl font-bold text-gray-800">Sarah Martin</h2>
                <p class="text-gray-500">Chef de Projet</p>
            </div>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-home mr-3"></i>Tableau de Bord
                        </a>
                    </li>
                    <li>
                        <a href="#projects" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-project-diagram mr-3"></i>Mes Projets
                        </a>
                    </li>
                    <li>
                        <a href="#team" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-users mr-3"></i>Équipe
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-chart-pie mr-3"></i>Rapports
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="mt-auto pt-6 border-t">
                <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>Déconnexion
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow p-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Chef de Projet</h1>
                <div class="flex items-center space-x-4">
                    <button id="newProjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-plus mr-2"></i>Nouveau Projet
                    </button>
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." class="bg-gray-100 rounded-full px-4 py-2 pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </header>

            <!-- Mes Projets Section -->
            <section id="projects" class="mb-8">
                <h2 class="text-2xl font-semibold mb-6">Mes Projets</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Projet 1 -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Gestion de Projet</h3>
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">En cours</span>
                        </div>
                        <p class="text-gray-600 mb-4">Développement d'une plateforme de gestion de projet collaborative</p>
                        
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Progression</span>
                                <span class="text-sm font-bold">65%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Marie" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pierre" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                            </div>
                            <span class="text-sm text-gray-500">Échéance: 15/03/2024</span>
                        </div>
                    </div>

                    <!-- Projet 2 -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Application Mobile</h3>
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">En attente</span>
                        </div>
                        <p class="text-gray-600 mb-4">Création d'une application mobile de productivité</p>
                        
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Progression</span>
                                <span class="text-sm font-bold">40%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Jean" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Sophie" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                            </div>
                            <span class="text-sm text-gray-500">Échéance: 30/06/2024</span>
                        </div>
                    </div>

                    <!-- Projet 3 -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Refonte Site Web</h3>
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">En retard</span>
                        </div>
                        <p class="text-gray-600 mb-4">Modernisation de l'interface utilisateur et optimisation des performances</p>
                        
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500">Progression</span>
                                <span class="text-sm font-bold">25%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <img src="https://randomuser.me/api/portraits/women/89.jpg" alt="Emma" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/men/81.jpg" alt="Lucas" 
                                     class="w-8 h-8 rounded-full border-2 border-white">
                            </div>
                            <span class="text-sm text-gray-500">Échéance: 15/02/2024</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Équipe et Membres -->
            <section id="team" class="mt-8 bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Équipe</h2>
                    <button id="addMemberBtn" class="text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-lg">
                        <i class="fas fa-user-plus mr-2"></i>Ajouter Membre
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="flex items-center bg-gray-100 rounded-lg p-4">
                        <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Marie" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="font-medium">Marie Dupont</h3>
                            <p class="text-sm text-gray-500">Designer</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-gray-100 rounded-lg p-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pierre" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="font-medium">Pierre Martin</h3>
                            <p class="text-sm text-gray-500">Développeur</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-gray-100 rounded-lg p-4">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Jean" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="font-medium">Jean Dubois</h3>
                            <p class="text-sm text-gray-500">Développeur Backend</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-gray-100 rounded-lg p-4">
                        <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Sophie" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="font-medium">Sophie Leroy</h3>
                            <p class="text-sm text-gray-500">Testeur</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal for New Project -->
    <div id="newProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 w-11/12 max-w-2xl">
            <h2 class="text-2xl font-bold mb-6">Créer un Nouveau Projet</h2>
            <form id="newProjectForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Nom du Projet</label>
                        <input type="text" class="w-full px-3 py-2 border rounded-lg" placeholder="Entrez le nom du projet" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Date Limite</label>
                        <input type="date" class="w-full px-3 py-2 border rounded-lg" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-gray-700 mb-2">Description du Projet</label>
                    <textarea class="w-full px-3 py-2 border rounded-lg" rows="4" placeholder="Décrivez le projet" required></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700 mb-2">Membres de l'Équipe</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Marie" 
                                     class="w-8 h-8 rounded-full mr-2">
                                Marie Dupont
                            </span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pierre" 
                                     class="w-8 h-8 rounded-full mr-2">
                                Pierre Martin
                            </span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Jean" 
                                     class="w-8 h-8 rounded-full mr-2">
                                Jean Dubois
                            </span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/women/67.jpg" alt="Sophie" 
                                     class="w-8 h-8 rounded-full mr-2">
                                Sophie Leroy
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" id="cancelProjectBtn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Annuler</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Créer Projet</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Interactions
        document.getElementById('newProjectBtn').addEventListener('click', () => {
            document.getElementById('newProjectModal').classList.remove('hidden');
            document.getElementById('newProjectModal').classList.add('flex');
        });

        document.getElementById('cancelProjectBtn').addEventListener('click', () => {
            document.getElementById('newProjectModal').classList.remove('flex');
            document.getElementById('newProjectModal').classList.add('hidden');
        });

        document.getElementById('newProjectForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Projet créé avec succès!');
            document.getElementById('newProjectModal').classList.remove('flex');
            document.getElementById('newProjectModal').classList.add('hidden');
        });
    </script>
</body>
</html>
