<?php 
session_start();
require_once __DIR__ . "/../controller/classes/configDB.php";
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
        }
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php if(isset($_SESSION["projectCreated"])): ?>
    <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?php echo $_SESSION["projectCreated"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["projectCreated"]); endif; ?>
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white shadow-xl p-6">
            <div class="mb-10 text-center">
                <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username'] ?>&background=0D8ABC&color=fff" alt="Logo" class="mx-auto w-24 h-24 rounded-full object-cover">
                <h2 class="mt-4 text-xl font-bold text-gray-800"><?=$_SESSION['username'] ?></h2>
                <p class="text-gray-500"><?=$_SESSION['email'] ?></p>
            </div>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" id="dashboard-link" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-home mr-3"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" id="Rapports" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-chart-pie mr-3"></i>Reports
                        </a>
                    </li>
                    <li>
                        <a href="#projects" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-project-diagram mr-3"></i>My Projects
                        </a>
                    </li>
                    <li>
                        <a href="#team" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-users mr-3"></i>Team
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="MainContent" class="flex-grow p-8">
            <!-- Dashboard Section -->
            <div id="dashboard-section">
                <header class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Project Manager Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <button id="newProjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-plus mr-2"></i>New Project
                        </button>
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="bg-gray-100 rounded-full px-4 py-2 pl-10 w-64">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                </header>

                <!-- Existing Projects Section -->
                <section id="projects" class="mb-8">
                    <h2 class="text-2xl font-semibold mb-6">My Projects</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Projet 1 -->
                         <?php $myProjects= $user->getUserProjects() ;
                        //  isset($myProjects) ? die("malga walo") : die("lga"); 
                         foreach($myProjects as $project) {
                          ?>
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold"><?= $project["name"] ?></h3>
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">In Progress</span>
                            </div>
                            <p class="text-gray-600 mb-4"><?= $project["description"] ?></p>
                            

                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Marie" 
                                         class="w-8 h-8 rounded-full border-2 border-white">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pierre" 
                                         class="w-8 h-8 rounded-full border-2 border-white">
                                </div>
                                <span class="text-sm text-gray-500"><?= $project["dueDate"] ?></span>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </section>

                <!-- Team Section -->
                <section id="team" class="mt-8 bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                    
                        <h2 class="text-xl font-semibold">Team</h2>
                        <button id="addMemberBtn" class="text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-lg">
                            <i class="fas fa-user-plus mr-2"></i>Add Member
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                       
                            <?php 
                         $db = new Database();
                         $conn = $db->getConnection();
                         $query = "SELECT * FROM users";
                         $result = $conn->query($query);
                              
                         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "
                             <div class='flex items-center bg-gray-100 rounded-lg p-4'>

                            <img src='https://randomuser.me/api/portraits/women/50.jpg' alt='Marie' class='w-12 h-12 rounded-full mr-4'>
                            <div>
                           <h3 class='font-medium'>" . $row['username'] . "</h3>
                                <p class='text-sm text-gray-500'>Designer</p>
                                </div>
                        </div>
                            ";
                         }
                        ?> 
                                
                    </div>
                </section>
            </div>

            <!-- Reports Section -->
            <div id="reports-section" class="hidden">
                <header class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Performance Reports</h1>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <select class="bg-white border rounded-lg px-4 py-2">
                                <option>Last Quarter</option>
                                <option>Last 6 Months</option>
                                <option>Full Year</option>
                            </select>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                    </div>
                </header>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 text-center">
                        <i class="fas fa-project-diagram text-blue-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">5</h3>
                        <p class="text-gray-500">Active Projects</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 text-center">
                        <i class="fas fa-tasks text-green-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">42</h3>
                        <p class="text-gray-500">Completed Tasks</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 text-center">
                        <i class="fas fa-clock text-yellow-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">12</h3>
                        <p class="text-gray-500">Ongoing Tasks</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">3</h3>
                        <p class="text-gray-500">Delayed Projects</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Project Progress Chart -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Project Progress</h2>
                        <canvas id="projectProgressChart" width="400" height="300"></canvas>
                    </div>

                    <!-- Team Performance Chart -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Team Performance</h2>
                        <canvas id="teamPerformanceChart" width="400" height="300"></canvas>
                    </div>

                    <!-- Task Distribution Chart -->
                    <div class="bg-white rounded-xl shadow-md p-6 col-span-full">
                        <h2 class="text-xl font-semibold mb-4">Task Distribution</h2>
                        <canvas id="taskDistributionChart" width="800" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for New Project -->
    <div id="newProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 w-11/12 max-w-2xl">
            <h2 class="text-2xl font-bold mb-6">Create a New Project</h2>
            <form id="newProjectForm" method="POST" action="../controller/project.controller.php">
                <input type="hidden" name="creatProject" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Project Name</label>
                        <input type="text" name="project_name" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea name="project_description" class="w-full border rounded-lg px-3 py-2" rows="4" required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="isPublic" value="1" checked class="form-checkbox text-blue-600">
                            <span class="text-gray-700">Public Project</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Public projects are visible to all users</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Due Date</label>
                        <input type="date" name="dueDate" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="closeModal('newProjectModal')">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Project</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle between Dashboard and Reports
        document.getElementById('Rapports').addEventListener('click', () => {
            document.getElementById('dashboard-section').classList.add('hidden');
            document.getElementById('reports-section').classList.remove('hidden');
        });

        document.getElementById('dashboard-link').addEventListener('click', () => {
            document.getElementById('reports-section').classList.add('hidden');
            document.getElementById('dashboard-section').classList.remove('hidden');
        });

        // Charts
        // Project Progress Chart
        const projectProgressCtx = document.getElementById('projectProgressChart').getContext('2d');
        new Chart(projectProgressCtx, {
            type: 'bar',
            data: {
                labels: ['Project Management', 'Mobile App', 'Website', 'CRM', 'Marketing'],
                datasets: [{
                    label: 'Progress (%)',
                    data: [65, 40, 25, 80, 55],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Team Performance Chart
        const teamPerformanceCtx = document.getElementById('teamPerformanceChart').getContext('2d');
        new Chart(teamPerformanceCtx, {
            type: 'radar',
            data: {
                labels: ['Marie', 'Pierre', 'Jean', 'Sophie', 'Emma'],
                datasets: [{
                    label: 'Performance',
                    data: [85, 75, 65, 90, 70],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                scale: {
                    r: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Task Distribution Chart
        const taskDistributionCtx = document.getElementById('taskDistributionChart').getContext('2d');
        new Chart(taskDistributionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Completed Tasks',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    },
                    {
                        label: 'Ongoing Tasks',
                        data: [2, 3, 20, 5, 1, 4],
                        borderColor: 'rgb(255, 205, 86)',
                        tension: 0.1
                    },
                    {
                        label: 'Delayed Tasks',
                        data: [1, 2, 1, 3, 0, 2],
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Task Distribution by Month'
                    }
                }
            }
        });

        // Modal Interactions
        document.getElementById('newProjectBtn').addEventListener('click', () => {
            document.getElementById('newProjectModal').classList.remove('hidden');
            document.getElementById('newProjectModal').classList.add('flex');
        });

        document.getElementById('cancelProjectBtn').addEventListener('click', () => {
            document.getElementById('newProjectModal').classList.remove('flex');
            document.getElementById('newProjectModal').classList.add('hidden');
        });
    </script>
</body>
</html>
