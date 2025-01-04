<?php
require_once "controller/classes/project.php";
require_once "controller/classes/configDB.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEST Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ activeTab: 'projects' }">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">GEST Dashboard</h1>
            <div class="flex items-center space-x-4">
                <button @click="activeTab = 'projects'" 
                    :class="activeTab === 'projects' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded transition">
                    Public Projects
                </button>
                <a href="project-create.php">
                <button 
                    :class="activeTab === 'create' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded transition">
                    Create Project
                </button>
                </a>
                
                <button @click="activeTab = 'requests'" 
                    :class="activeTab === 'requests' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded transition">
                    Join Requests
                </button>

                <div class="border-l pl-4 ml-4 flex space-x-3">
                    <a href="auth/login.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Login</a>
                    <a href="auth/register.php" class="border-2 border-blue-600 text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-50 transition duration-200">Sign Up</a>
                </div>
            </div>
        </header>

        <!-- Public Projects Section -->
        <section x-show="activeTab === 'projects'" class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Public Projects</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Project Card 1 -->
                 <?php 
                 $db = new Database();
                 $projects = Project::getPublicProjects($db) ;
                 foreach ($projects as $project) {
                 ?>
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <h3 class="text-xl font-bold mb-2"><?= $project['name']?></h3>
                    <p class="text-gray-600 mb-4"><?= $project['description']?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Team Size: 5/10</span>
                        
                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition text-sm">
                            Request to Join
                        </button>
                    </div>
                </div>
                <?php }?>
            </div>
        </section>

        <!-- My Requests Section -->
        <section x-show="activeTab === 'requests'" class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">My Join Requests</h2>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-left p-2">Project</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-2">Web Development Project</td>
                        <td class="p-2">
                            <span class="text-yellow-600">Pending</span>
                        </td>
                        <td class="p-2">
                            <button class="text-red-500 hover:text-red-700">Cancel Request</button>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-2">Mobile App Development</td>
                        <td class="p-2">
                            <span class="text-green-600">Approved</span>
                        </td>
                        <td class="p-2">
                            <button class="text-blue-500 hover:text-blue-700">View Project</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2">AI Research Project</td>
                        <td class="p-2">
                            <span class="text-red-600">Rejected</span>
                        </td>
                        <td class="p-2">
                            <button class="text-gray-500 hover:text-gray-700">Request Again</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>