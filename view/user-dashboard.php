<?php
// Define base path for assets
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/Anouar_elbarry-projet';
$assets_path = $base_path . '/Assets';
$img_path = $assets_path . '/img';

// Static internet images
$logo_url = 'https://via.placeholder.com/150/00FF00/808080?text=Logo';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Utilisateur</title>
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
                <img src="<?php echo $logo_url; ?>" alt="Logo" class="mx-auto w-24 h-24 rounded-full object-cover">
                <h2 class="mt-4 text-xl font-bold text-gray-800">John Doe</h2>
                <p class="text-gray-500">Membre de l'équipe</p>
            </div>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-home mr-3"></i>Tableau de Bord
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-tasks mr-3"></i>Mes Tâches
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-project-diagram mr-3"></i>Projets
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-calendar-alt mr-3"></i>Calendrier
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
                <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." class="bg-gray-100 rounded-full px-4 py-2 pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Mes Tâches Récentes -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Mes Tâches Récentes</h2>
                    <ul class="space-y-3">
                        <li class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">Développer l'interface utilisateur</h3>
                                <p class="text-sm text-gray-500">Projet: Gestion de Projet</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">En cours</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">Réunion d'équipe</h3>
                                <p class="text-sm text-gray-500">Projet: Communication</p>
                            </div>
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Terminé</span>
                        </li>
                    </ul>
                </div>

                <!-- Projets en Cours -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Projets en Cours</h2>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-code text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium">Gestion de Projet</h3>
                                    <p class="text-sm text-gray-500">3 membres</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">65%</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium">Application Mobile</h3>
                                    <p class="text-sm text-gray-500">2 membres</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">40%</span>
                        </li>
                    </ul>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Statistiques</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-blue-600">12</h3>
                            <p class="text-sm text-gray-500">Tâches Totales</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-green-600">8</h3>
                            <p class="text-sm text-gray-500">Tâches Terminées</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-yellow-600">3</h3>
                            <p class="text-sm text-gray-500">En Cours</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-red-600">1</h3>
                            <p class="text-sm text-gray-500">En Retard</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $assets_path . '/script/user-dashboard.js'; ?>"></script>
</body>
</html>
