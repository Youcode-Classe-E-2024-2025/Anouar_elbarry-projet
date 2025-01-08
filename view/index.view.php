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
$IN_progresstasks = Task::getTaskByStatus($db,'IN_PROGRESS',$project_id);
$TODOtasks = Task::getTaskByStatus($db,'TODO',$project_id);
$DONEtasks = Task::getTaskByStatus($db,'DONE',$project_id);

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

    <?php if(isset($_SESSION["errorT"])): ?>
    <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span><?php echo $_SESSION["errorT"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["errorT"]); endif; ?>
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
                    <button id="addCategoryBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition" onclick="toggleCategoryModal()">
                        <i class="fas fa-folder-plus mr-2"></i>New Category
                    </button>
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

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-xl bg-white transform transition-all duration-300 opacity-0 translate-y-4" id="modalContent">
            <div class="flex justify-between items-center pb-6 border-b">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900">Create New Category</h3>
                    <p class="mt-1 text-sm text-gray-500">Add a new category to organize your tasks</p>
                </div>
                <button onclick="toggleCategoryModal()" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-full p-2 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="../controller/category.controller.php" method="POST" class="space-y-6 mt-6">
                <div>
                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="categoryName" required
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="Enter category name">
                    </div>
                </div>
                <div>
                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-align-left text-gray-400"></i>
                        </div>
                        <textarea name="description" id="categoryDescription" rows="4" required
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="Enter category description"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <button type="button" onclick="toggleCategoryModal()"
                        class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCategoryModal() {
            const modal = document.getElementById('categoryModal');
            const modalContent = document.getElementById('modalContent');
            
            if (modal.classList.contains('hidden')) {
                // Show modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('opacity-0', 'translate-y-4');
                }, 10);
            } else {
                // Hide modal
                modalContent.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.getElementById('categoryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleCategoryModal();
            }
        });
    </script>

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
            <div class="bg-gray-50 rounded-lg p-4" id="TODO" ondrop="drop(event)" ondragover="allowDrop(event)">
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
                                    'HIGH' => 'bg-red-500',
                                    'MEDIUM' => 'bg-yellow-500',
                                    'LOW' => 'bg-green-500',
                                    default => 'bg-gray-500'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-move" 
                         draggable="true" 
                         ondragstart="drag(event)" 
                         id="task-<?= $task['id'] ?>"
                         data-task-id="<?= $task['id'] ?>"
                         data-current-status="TODO">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full <?= $priorityColor ?>"></span>
                                    <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                </div>
                                <?php if($task['tag']): ?>
                                <div class="mt-1.5">
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs inline-flex items-center">
                                        <i class="fas fa-tag text-[10px] mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="components/task_details.php?task_id=<?= $task['id'] ?>"
                                        class="p-1.5 text-gray-500 hover:text-blue-600 rounded-full hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a  href="../../controller/task.controller.php?task_id=<?= $task['id'] ?>" 
                                        class="p-1.5 text-gray-500 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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
            <div class="bg-gray-50 rounded-lg p-4" id="IN_PROGRESS" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">In Progress</h2>
                    <span class="text-sm text-gray-500"><?= count($IN_progresstasks)?> Tasks</span>
                </div>
                <div class="space-y-4">
                <?php 
                            foreach($IN_progresstasks as $task): 
                                // Determine priority color
                                $priorityColor = match($task['priority']) {
                                    'HIGH' => 'bg-red-500',
                                    'MEDIUM' => 'bg-yellow-500',
                                    'LOW' => 'bg-green-500',
                                    default => 'bg-gray-500'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-move" 
                         draggable="true" 
                         ondragstart="drag(event)" 
                         id="task-<?= $task['id'] ?>"
                         data-task-id="<?= $task['id'] ?>"
                         data-current-status="IN_PROGRESS">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full <?= $priorityColor ?>"></span>
                                    <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                </div>
                                <?php if($task['tag']): ?>
                                <div class="mt-1.5">
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs inline-flex items-center">
                                        <i class="fas fa-tag text-[10px] mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a  href="components/task_details.php?task_id=<?= $task['id'] ?>"
                                        class="p-1.5 text-gray-500 hover:text-blue-600 rounded-full hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="../../controller/task.controller.php?task_id=<?= $task['id'] ?>" 
                                        class="p-1.5 text-gray-500 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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
            <div class="bg-gray-50 rounded-lg p-4" id="DONE" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Done</h2>
                    <span class="text-sm text-gray-500"><?= count($DONEtasks)?> tasks</span>
                </div>
                <div class="space-y-4">
                <?php 
                            foreach($DONEtasks as $task): 
                                // Determine priority color
                                $priorityColor = match($task['priority']) {
                                    'HIGH' => 'bg-red-500',
                                    'MEDIUM' => 'bg-yellow-500',
                                    'LOW' => 'bg-green-500',
                                    default => 'bg-gray-500'
                                };
                            ?>
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-move" 
                         draggable="true" 
                         ondragstart="drag(event)" 
                         id="task-<?= $task['id'] ?>"
                         data-task-id="<?= $task['id'] ?>"
                         data-current-status="DONE">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full <?= $priorityColor ?>"></span>
                                    <h3 class="font-medium text-gray-800"><?= $task['title'] ?></h3>
                                </div>
                                <?php if($task['tag']): ?>
                                <div class="mt-1.5">
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs inline-flex items-center">
                                        <i class="fas fa-tag text-[10px] mr-1"></i><?= $task['tag'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="components/task_details.php?task_id=<?= $task['id'] ?>"
                                        class="p-1.5 text-gray-500 hover:text-blue-600 rounded-full hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="../../controller/task.controller.php?task_id=<?= $task['id'] ?>" 
                                        class="p-1.5 text-gray-500 hover:text-red-600 rounded-full hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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
        <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-5xl mx-4 transform transition-all duration-300 scale-95 opacity-0" id="taskModalContent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">Create New Task</h3>
                <button onclick="toggleTaskModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
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
                    
                    foreach($projectMembers as $member):
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
                    <button type="button" onclick="toggleTaskModal()" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Task Modal Functions
        function toggleTaskModal() {
            const modal = document.getElementById('taskModal');
            const modalContent = document.getElementById('taskModalContent');
            
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex'; 
                setTimeout(() => {
                    modalContent.classList.remove('opacity-0', 'translate-y-4');
                }, 10);
            } else {
                modalContent.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none'; 
                }, 300);
            }
        }

        // Make sure this runs after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listener to the add task button
            const addTaskBtn = document.getElementById('addTaskBtn');
            if (addTaskBtn) {
                addTaskBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Add task button clicked'); 
                    toggleTaskModal();
                });
            }

            // Close modal when clicking outside
            const taskModal = document.getElementById('taskModal');
            if (taskModal) {
                taskModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        toggleTaskModal();
                    }
                });
            }
        });
    </script>

    <script>
        // Drag and Drop Functions
        function allowDrop(ev) {
            ev.preventDefault();
            // Add visual feedback for drop target
            ev.currentTarget.classList.add('bg-gray-100');
        }

        function drag(ev) {
            ev.dataTransfer.setData("taskId", ev.target.getAttribute("data-task-id"));
            ev.dataTransfer.setData("currentStatus", ev.target.getAttribute("data-current-status"));
            
            // Add dragging style
            ev.target.classList.add('opacity-50');
        }

        function drop(ev) {
            ev.preventDefault();
            const taskId = ev.dataTransfer.getData("taskId");
            const currentStatus = ev.dataTransfer.getData("currentStatus");
            const newStatus = ev.currentTarget.id;
            const taskElement = document.getElementById(`task-${taskId}`);
            
            // Remove visual feedback
            document.querySelectorAll('.bg-gray-100').forEach(el => el.classList.remove('bg-gray-100'));
            taskElement.classList.remove('opacity-50');

            // Don't do anything if dropped in the same column
            if (currentStatus === newStatus) return;

            // Update task status in the database
            updateTaskStatus(taskId, newStatus).then(response => {
                if (response.success) {
                    // Move the task element to the new column
                    ev.currentTarget.querySelector('.space-y-4').appendChild(taskElement);
                    taskElement.setAttribute('data-current-status', newStatus);
                    
                    // Update task counts
                    updateTaskCounts();
                }
            });
        }

        function updateTaskCounts() {
            const columns = ['TODO', 'IN_PROGRESS', 'DONE'];
            columns.forEach(status => {
                const column = document.getElementById(status);
                const count = column.querySelectorAll('[draggable="true"]').length;
                column.querySelector('.text-sm').textContent = `${count} tasks`;
            });
        }

        async function updateTaskStatus(taskId, newStatus) {
            try {
                const response = await fetch('../controller/task.controller.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}&new_status=${newStatus}&action=update_status`
                });
                
                const data = await response.json();
                if (data.success) {
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                    successAlert.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Task status updated successfully</span>
                        </div>
                    `;
                    document.body.appendChild(successAlert);
                    setTimeout(() => successAlert.remove(), 3000);
                }
                return data;
            } catch (error) {
                console.error('Error updating task status:', error);
                return { success: false };
            }
        }

        // Prevent dragover class from sticking
        document.querySelectorAll('[ondrop]').forEach(column => {
            column.addEventListener('dragleave', (e) => {
                if (e.target === column) {
                    column.classList.remove('bg-gray-100');
                }
            });
        });
    </script>
</body>
</html>