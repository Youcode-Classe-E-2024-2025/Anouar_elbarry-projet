<?php 
session_start();
require_once __DIR__ . "/../controller/classes/configDB.php";
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: auth/login.php');
    exit();
}

// Initialize user object
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);

// Get user projects once and store the count
$userProjects = $user->getUserProjects();
$uniqueTeamMembers = [];

foreach ($userProjects as $project) {
    $projectMembers = $user->getProjectMembers($project['id']);
    foreach ($projectMembers as $member) {
        // Use member ID as key to ensure uniqueness
        $uniqueTeamMembers[$member['id']] = $member;
    }
}

$teamcount = count($uniqueTeamMembers);

$projectCount = is_array($userProjects) ? count($userProjects) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Success/Error Messages -->
    <?php if(isset($_SESSION["success"])): ?>
    <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?php echo $_SESSION["success"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["success"]); endif; ?>

    <?php if(isset($_SESSION["error"])): ?>
    <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span><?php echo $_SESSION["error"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["error"]); endif; ?>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-xl p-6">
            <div class="mb-10 text-center">
                <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username'] ?>&background=0D8ABC&color=fff" 
                     alt="Profile" class="mx-auto w-24 h-24 rounded-full object-cover">
                <h2 class="mt-4 text-xl font-bold"><?=$_SESSION['username'] ?></h2>
                <p class="text-gray-500"><?=$_SESSION['email'] ?></p>
                <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    <?=$_SESSION['userRole'] ?>
                </span>
            </div>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#dashboard" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-home mr-3"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#my-projects" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-project-diagram mr-3"></i>My Projects
                        </a>
                    </li>
                    <?php if($_SESSION['userRole'] != 'PROJECT_MANAGER'): ?>
                    <li>
                        <a href="index.view.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-tasks mr-3"></i>My Tasks
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                    <li>
                        <a href="#team-management" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-users-cog mr-3"></i>Team Management
                        </a>
                    </li>
                    
                    <li>
                        <a href="#project-requests" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-envelope mr-3"></i>Project Requests
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="#reports-section" id="Rapports" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-chart-bar mr-3"></i>Reports
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="../controller/logout.php" class="flex items-center p-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-grow p-8">
            <!-- Header -->
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                    <button id="newProjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-plus mr-2"></i>New Project
                    </button>
                    <?php endif; ?>
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="bg-gray-100 rounded-full px-4 py-2 pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </header>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-project-diagram text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Active Projects</h3>
                            <p class="text-2xl font-semibold"><?= $projectCount ?></p>
                        </div>
                    </div>
                </div>
                <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-users text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Team Members</h3>
                            <p class="text-2xl font-semibold"><?= $teamcount ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clipboard-check text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Completed Tasks</h3>
                            <p class="text-2xl font-semibold">45</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-clock text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Pending Requests</h3>
                            <p class="text-2xl font-semibold">3</p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-tasks text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">My Tasks</h3>
                            <p class="text-2xl font-semibold">8</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Pending Tasks</h3>
                            <p class="text-2xl font-semibold">3</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-check-circle text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Completed Tasks</h3>
                            <p class="text-2xl font-semibold">5</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Projects Section -->
            <section id="my-projects" class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">
                        <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                            My Projects
                        <?php else: ?>
                            Projects I'm Working On
                        <?php endif; ?>
                    </h2>
                    <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                    <button id="newProjectBtn2" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-plus mr-2"></i>New Project
                    </button>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    foreach($userProjects as $project): 
                    ?>
                    <a href="index.view.php?project_id=<?= $project['id'] ?>" class="block hover:transform hover:scale-105 transition-transform duration-200">
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold"><?= htmlspecialchars($project["name"]) ?></h3>
                                    <?php if($_SESSION['userRole'] != 'PROJECT_MANAGER'): ?>
                                    <p class="text-sm text-gray-500">
                                        Project Manager: 
                                        <?php 
                                        $members = $user->getProjectMembers($project['id']);
                                        foreach($members as $member) {
                                            if($member['role'] == 'PROJECT_MANAGER') {
                                                echo $member['username'];
                                                break;
                                            }
                                        }
                                        ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Active</span>
                            </div>
                            <p class="text-gray-600 mb-4"><?= htmlspecialchars($project["description"]) ?></p>
                            
                            <!-- Project Details -->
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span>Due: <?= date('M j, Y', strtotime($project["dueDate"])) ?></span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-users mr-2"></i>
                                    <span>Team Members:</span>
                                </div>
                                
                                <div class="flex items-center -space-x-2">
                                    <?php 
                                    $projectMembers = $user->getProjectMembers($project['id']);
                                    foreach(array_slice($projectMembers, 0, 3) as $member): 
                                    ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= $member['username'] ?>" 
                                         alt="<?= $member['username'] ?>" 
                                         class="w-8 h-8 rounded-full border-2 border-white"
                                         title="<?= $member['username'] ?> (<?= $member['role'] ?>)">
                                    <?php endforeach; ?>
                                    <?php if(count($projectMembers) > 3): ?>
                                    <span class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs">
                                        +<?= count($projectMembers) - 3 ?>
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                                <div class="flex justify-end pt-2">
                                    <button class="text-blue-500 hover:text-blue-600 text-sm">
                                        <i class="fas fa-cog mr-1"></i>Manage Project
                                    </button>
                                </div>
                                <?php else: ?>
                                <div class="flex justify-end pt-2">
                                    <button class="text-blue-500 hover:text-blue-600 text-sm">
                                        <i class="fas fa-tasks mr-1"></i>View Tasks
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Tasks Section - Only visible for team members -->
            <?php if($_SESSION['userRole'] != 'PROJECT_MANAGER'): ?>
            <section id="my-tasks" class="mb-8">
                <h2 class="text-2xl font-semibold mb-6">My Tasks</h2>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Task rows will be populated here -->
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>

            <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
            <!-- Project Requests Section -->
            <section id="project-requests" class="mb-8">
                <h2 class="text-2xl font-semibold mb-6">Project Requests</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample request card - Replace with actual data -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold">Website Redesign</h3>
                                <p class="text-sm text-gray-500">From: John Doe</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-xs">Pending</span>
                        </div>
                        <p class="text-gray-600 mb-4">Request to join as a Frontend Developer</p>
                        <div class="flex justify-end space-x-2">
                            <button class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Accept</button>
                            <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Decline</button>
                        </div>
                    </div>
                </div>
            </section>

             <!-- Reports Section -->
             <section id="reports-section" class="mb-8">
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
            </section>
            <?php endif ?>
        </div>
    </div>

    <!-- Include your modal for new project creation -->
    <?php include 'components/new-project-modal.php'; ?>

    <script>
        // Show/Hide sections based on navigation
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', (e) => {
                if (e.currentTarget.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = e.currentTarget.getAttribute('href').slice(1);
                    document.querySelectorAll('section').forEach(section => {
                        section.style.display = 'none';
                    });
                    document.getElementById(targetId).style.display = 'block';
                }
            });
        });

        // Show/Hide new project modal
        const newProjectBtn = document.getElementById('newProjectBtn');
        const newProjectModal = document.getElementById('newProjectModal');
        if (newProjectBtn && newProjectModal) {
            newProjectBtn.addEventListener('click', () => {
                newProjectModal.classList.remove('hidden');
                newProjectModal.classList.add('flex');
            });
        }
        const newProjectBtn2 = document.getElementById('newProjectBtn2');
        if (newProjectBtn2 && newProjectModal) {
            newProjectBtn2.addEventListener('click', () => {
                newProjectModal.classList.remove('hidden');
                newProjectModal.classList.add('flex');
            });
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

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

    </script>
</body>
</html>
