        <!-- Main Content -->
<div class="rapport hidden flex-grow p-8">
    <header class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Rapports de Performance</h1>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <select class="bg-white border rounded-lg px-4 py-2">
                    <option>Dernier Trimestre</option>
                    <option>6 Derniers Mois</option>
                    <option>Année Complète</option>
                </select>
            </div>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-download mr-2"></i>Exporter
            </button>
        </div>
    </header>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-project-diagram text-blue-500 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">5</h3>
            <p class="text-gray-500">Projets Actifs</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-tasks text-green-500 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">42</h3>
            <p class="text-gray-500">Tâches Terminées</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-clock text-yellow-500 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">12</h3>
            <p class="text-gray-500">Tâches en Cours</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">3</h3>
            <p class="text-gray-500">Projets en Retard</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Project Progress Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Progression des Projets</h2>
            <canvas id="projectProgressChart" width="400" height="300"></canvas>
        </div>

        <!-- Team Performance Chart -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Performance de l'Équipe</h2>
            <canvas id="teamPerformanceChart" width="400" height="300"></canvas>
        </div>

        <!-- Task Distribution Chart -->
        <div class="bg-white rounded-xl shadow-md p-6 col-span-full">
            <h2 class="text-xl font-semibold mb-4">Distribution des Tâches</h2>
            <canvas id="taskDistributionChart" width="800" height="300"></canvas>
        </div>
    </div>
</div>
