<?php 
session_start();
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";
require_once __DIR__ . "/../controller/classes/category.php";
require_once __DIR__ . "/../controller/classes/configDB.php";
require_once __DIR__ . "/../controller/classes/task.php";

$db = new Database();
$conn = $db->getConnection();
// Initialize user
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $project_id  = $_GET['project_id'] ;
    $_SESSION["project_id"] = $project_id ;
}
$project = $user->getProjectById($project_id);
if (!$project) {
    header('Location: dashboard.php?error=project_not_found');
    exit();
}
$projectMembers = $user->getProjectMembers($project_id);
$category = new Category();
$categories = $category::getAll($db) ;
$IN_progresstasks = Task::getTaskByStatus($db,'IN_PROGRESS');
$TODOtasks = Task::getTaskByStatus($db,'TODO');
$DONEtasks = Task::getTaskByStatus($db,'DONE');

$AllTasks = count($DONEtasks) + count($IN_progresstasks) + count($TODOtasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tasks - Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
     <!-- Success/Error Messages -->
     <?php if(isset($_SESSION["successT"])): ?>
    <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?php echo $_SESSION["successT"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["successT"]); endif; ?>

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
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $project["name"]?></h1>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Active</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="addTaskBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-plus mr-2"></i>New Task
                    </button>
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Project Overview -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-start">
                <div class="space-y-2">
                    <p class="text-gray-600"><?= $project["description"]?></p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span><i class="fas fa-calendar mr-2"></i>Due: <?= $project["dueDate"]?></span>
                        <span><i class="fas fa-users mr-2"></i><?= count($projectMembers)?> members</span>
                        <span><i class="fas fa-tasks mr-2"></i><?= $AllTasks?> tasks</span>
                    </div>
                </div>
                <div class="flex -space-x-2">
                    <?php foreach(array_slice($projectMembers, 0, 3) as $member): ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['username']) ?>" alt="Team Member" class="w-8 h-8 rounded-full border-2 border-white">
                    <?php endforeach ?>
                    <?php if(count($projectMembers) > 3) :?>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs text-gray-600">+<?php count($projectMembers) ?></div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Task Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- To Do Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">To Do</h2>
                    <span class="text-sm text-gray-500"><?= count($TODOtasks)?> tasks</span>
                </div>
                <!-- Task Cards -->
                <div class="space-y-4">
                    <?php 
                            foreach($TODOtasks as $task): 
                                // Determine priority color
                                $priorityColor = match($task['priority']) {
                                    'HIGH' => 'bg-red-100 text-red-800',
                                    'MEDIUM' => 'bg-yellow-100 text-yellow-800',
                                    'LOW' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                <div class="flex gap-2 mt-2">
                                    <span class="px-2 py-1 rounded-full text-xs <?= $priorityColor ?>">
                                        <i class="fas fa-flag mr-1"></i><?= $task['priority'] ?>
                                    </span>
                                    <?php if($task['tag']): ?>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs flex items-center">
                                        <i class="fas fa-tag mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?= $task['description'] ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due <?= $task['dueDate'] ?></span>
                            <div class="flex -space-x-2">
                                <?php 
                                $Taskmembers = Task::getTaskMembers($db, $task['id'], $project_id);
                                // Display first 3 members
                                foreach(array_slice($Taskmembers, 0, 3) as $member): ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= $member['username']?>" 
                                         alt="<?= $member['username'] ?>" 
                                         class="w-6 h-6 rounded-full border-2 border-white">
                                <?php endforeach; 
                                // Show count if more than 3 members
                                if(count($Taskmembers) > 3): ?>
                                    <span class="w-6 h-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs">
                                        +<?= count($Taskmembers) - 3 ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">In Progress</h2>
                    <span class="text-sm text-gray-500"><?= count($IN_progresstasks)?> Tasks</span>
                </div>
                <div class="space-y-4">
                <?php 
                            foreach($IN_progresstasks as $task): 
                                // Determine priority color
                                $priorityColor = match($task['priority']) {
                                    'HIGH' => 'bg-red-100 text-red-800',
                                    'MEDIUM' => 'bg-yellow-100 text-yellow-800',
                                    'LOW' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                <div class="flex gap-2 mt-2">
                                    <span class="px-2 py-1 rounded-full text-xs <?= $priorityColor ?>">
                                        <i class="fas fa-flag mr-1"></i><?= $task['priority'] ?>
                                    </span>
                                    <?php if($task['tag']): ?>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs flex items-center">
                                        <i class="fas fa-tag mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?= $task['description'] ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due <?= $task['dueDate'] ?></span>
                            <div class="flex -space-x-2">
                                <?php 
                                $Taskmembers = Task::getTaskMembers($db, $task['id'], $project_id);
                                // Display first 3 members
                                foreach(array_slice($Taskmembers, 0, 3) as $member): ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= $member['username']?>" 
                                         alt="<?= $member['username'] ?>" 
                                         class="w-6 h-6 rounded-full border-2 border-white">
                                <?php endforeach; 
                                // Show count if more than 3 members
                                if(count($Taskmembers) > 3): ?>
                                    <span class="w-6 h-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs">
                                        +<?= count($Taskmembers) - 3 ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <!-- Done Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Done</h2>
                    <span class="text-sm text-gray-500"><?= count($DONEtasks)?> tasks</span>
                </div>
                <div class="space-y-4">
                <?php 
                            foreach($DONEtasks as $task): 
                                // Determine priority color
                                $priorityColor = match($task['priority']) {
                                    'HIGH' => 'bg-red-100 text-red-800',
                                    'MEDIUM' => 'bg-yellow-100 text-yellow-800',
                                    'LOW' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                <div class="flex gap-2 mt-2">
                                    <span class="px-2 py-1 rounded-full text-xs <?= $priorityColor ?>">
                                        <i class="fas fa-flag mr-1"></i><?= $task['priority'] ?>
                                    </span>
                                    <?php if($task['tag']): ?>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs flex items-center">
                                        <i class="fas fa-tag mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?= $task['description'] ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due <?= $task['dueDate'] ?></span>
                            <div class="flex -space-x-2">
                                <?php 
                                $Taskmembers = Task::getTaskMembers($db, $task['id'], $project_id);
                                // Display first 3 members
                                foreach(array_slice($Taskmembers, 0, 3) as $member): ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= $member['username']?>" 
                                         alt="<?= $member['username'] ?>" 
                                         class="w-6 h-6 rounded-full border-2 border-white">
                                <?php endforeach; 
                                // Show count if more than 3 members
                                if(count($Taskmembers) > 3): ?>
                                    <span class="w-6 h-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs">
                                        +<?= count($Taskmembers) - 3 ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Task Modal -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-5xl mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">Create New Task</h3>
                <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form class="space-y-6" method="POST" action="../controller/task.controller.php">
                <div class="grid grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Task Title</label>
                            <input name="title" type="text" placeholder="Enter task title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                            <textarea name="description" rows="3" placeholder="Enter task description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Due Date</label>
                                <input name="dueDate" type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Priority</label>
                                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Category</label>
                            <div class="flex gap-2">
                                <select id="categorySelect" name="category" class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Select a category</option>
                                <?php foreach($categories as $category): ?>    
                                    <option value="<?= $category['name'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach ?>
                                </select>
                                <button type="button" id="newCategoryBtn" onclick="toggleNewCategoryInput()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New
                                </button>
                            </div>
                            <!-- New Category Input (Hidden by default) -->
                            <div id="newCategoryInput" class="hidden mt-2">
                                <div class="flex items-center gap-2">
                                    <input type="text" id="newCategory" placeholder="Enter new category name..." class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <button type="button" onclick="addNewCategory()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                        Add
                                    </button>
                                    <button type="button" onclick="toggleNewCategoryInput()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Tags</label>
                            <div class="flex flex-wrap gap-2 mb-3" id="tagsContainer">
                                <!-- Tags will be added here dynamically -->
                            </div>
                            <div class="relative">
                                <input type="text" 
                                       id="tagInput" 
                                       name="tag" 
                                       placeholder="Type tag and press Enter (e.g., feature, bug, urgent)" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <input type="hidden" name="tags" id="tagsInput" value="">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-gray-700 mb-1 text-sm">Assign Team Members</label>
                            <div class="border rounded-lg p-3 max-h-48 overflow-y-auto">
                            <?php 
                    $allUsers = User::getUsers();
                    foreach($allUsers as $member):
                        if(($member['id'] != $_SESSION['userid']) && $member['role'] == "TEAM_MEMBER"):
                    ?>
                                <div class="flex items-center justify-between py-1.5 border-b">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=<?= $member["username"] ?>&background=0D8ABC&color=fff" 
                                             alt="<?= $member["username"] ?>" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium"><?= $member["username"] ?></p>
                                            <p class="text-xs text-gray-500"><?= $member["email"] ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="assignedTo[]" 
                                                   value="<?= $member['id'] ?>" 
                                                   class="form-checkbox h-4 w-4 text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                                        </label>
                                    </div>
                                </div>
                                <?php 
                                endif;
                                endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeTaskModal()" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get DOM elements
        const taskModal = document.getElementById('taskModal');
        const modalContent = document.getElementById('modalContent');
        const addTaskBtn = document.getElementById('addTaskBtn');

        // Show modal with animation
        function showTaskModal() {
            taskModal.classList.remove('hidden');
            taskModal.classList.add('flex');
            // Trigger animation after a small delay
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        // Close modal with animation
        function closeTaskModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            // Wait for animation to finish before hiding
            setTimeout(() => {
                taskModal.classList.remove('flex');
                taskModal.classList.add('hidden');
            }, 300);
        }

        // Show modal
        addTaskBtn.addEventListener('click', showTaskModal);

        // Close modal when clicking outside
        taskModal.addEventListener('click', (e) => {
            if (e.target === taskModal) {
                closeTaskModal();
            }
        });

        // Toggle new category input visibility
        function toggleNewCategoryInput() {
            const newCategoryInput = document.getElementById('newCategoryInput');
            const newCategoryBtn = document.getElementById('newCategoryBtn');
            const isHidden = newCategoryInput.classList.contains('hidden');
            
            if (isHidden) {
                newCategoryInput.classList.remove('hidden');
                document.getElementById('newCategory').focus();
                newCategoryBtn.classList.add('bg-gray-200');
            } else {
                newCategoryInput.classList.add('hidden');
                document.getElementById('newCategory').value = '';
                newCategoryBtn.classList.remove('bg-gray-200');
            }
        }
    </script>
</body>
</html>