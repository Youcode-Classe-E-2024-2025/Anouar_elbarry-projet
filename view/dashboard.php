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

$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
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
                <h2 class="mt-4 text-xl font-bold text-gray-800"><?=$_SESSION['username'] ?></h2>
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
                    <li>
                        <a href="#my-tasks" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-tasks mr-3"></i>My Tasks
                        </a>
                    </li>
                    <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                    <li>
                        <a href="#manage-projects" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-cog mr-3"></i>Manage Projects
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="#project-requests" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition">
                            <i class="fas fa-envelope mr-3"></i>Project Requests
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                        </a>
                    </li>
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
                            <p class="text-2xl font-semibold">12</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-tasks text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">My Tasks</h3>
                            <p class="text-2xl font-semibold">25</p>
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
                            <p class="text-2xl font-semibold">8</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-users text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500">Team Members</h3>
                            <p class="text-2xl font-semibold">15</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <section id="my-projects" class="mb-8">
                <h2 class="text-2xl font-semibold mb-6">My Projects</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $userProjects = $user->getUserProjects();
                    foreach($userProjects as $project): 
                    ?>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold"><?= $project["name"] ?></h3>
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Active</span>
                        </div>
                        <p class="text-gray-600 mb-4"><?= $project["description"] ?></p>
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <?php 
                                $projectMembers = $user->getProjectMembers($project['id']);
                                foreach(array_slice($projectMembers, 0, 3) as $member): 
                                ?>
                                <img src="https://ui-avatars.com/api/?name=<?= $member['username'] ?>" 
                                     alt="<?= $member['username'] ?>" 
                                     class="w-8 h-8 rounded-full border-2 border-white"
                                     title="<?= $member['username'] ?>">
                                <?php endforeach; ?>
                                <?php if(count($projectMembers) > 3): ?>
                                <span class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs">
                                    +<?= count($projectMembers) - 3 ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-sm text-gray-500">Due: <?= $project["dueDate"] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Tasks Section -->
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
                            <!-- Sample task rows - Replace with actual data -->
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Design Homepage</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">Website Redesign</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">2025-01-10</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        In Progress
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        High
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

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

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
