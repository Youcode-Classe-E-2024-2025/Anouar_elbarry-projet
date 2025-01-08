<?php
session_start();
require_once __DIR__ . "/../../controller/classes/user.php";
require_once __DIR__ . "/../../controller/classes/project.php";
require_once __DIR__ . "/../../controller/classes/category.php";
require_once __DIR__ . "/../../controller/classes/configDB.php";
require_once __DIR__ . "/../../controller/classes/task.php";


$db = new Database();
$conn = $db->getConnection();
$project_id = $_SESSION["project_id"];
// Initialize user
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $task_id  = $_GET['task_id'] ;
}
// get Project
$project = $user->getProjectById($project_id);
$task = Task::getTaskById($db,$task_id);
// get category ID
$catyId = $task['category_id'];
// get category
$category = new Category();
$category = $category::getByID($catyId,$db);
// get task members
$taskMembers = Task::getTaskMembers($db,$task_id,$project_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
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
    <?php unset($_SESSION["successTD"]); endif; ?>

    <?php if(isset($_SESSION["errorTD"])): ?>
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
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Tasks
                </a>
            </div>

            <!-- Task Header -->
            <div class="bg-white rounded-t-xl shadow-sm p-8 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <span class="w-4 h-4 rounded-full bg-yellow-500 block"></span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900"><?= $task['title'] ?></h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </button>
                        <a href="../../controller/task.controller.php?task_id=<?= $task['id'] ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-b-xl border-x border-b border-gray-200">
                <div class="grid grid-cols-3 gap-8 p-8">
                    <!-- Left Column -->
                    <div class="col-span-2 space-y-8">
                        <!-- Description -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                            <p class="text-gray-600 leading-relaxed">
                            <?= $task['description'] ?>
                            </p>
                        </div>

                        <!-- Task Progress -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Progress</h2>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">65% Complete</div>
                        </div>

                       <!-- Assigned Members -->
                       <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Assigned Members</h2>
                                <button class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-plus mr-2"></i>Add Member
                                </button>
                            </div>
                            <div class="space-y-4">
                                <?php if (!empty($taskMembers)): ?>
                                    <?php foreach ($taskMembers as $member): ?>
                                        <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                            <div class="flex items-center space-x-3">
                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['username']) ?>" 
                                                     alt="<?= htmlspecialchars($member['username']) ?>" 
                                                     class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($member['username']) ?></p>
                                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($member['email']) ?></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs px-2 py-1 rounded-full <?= $member['role'] === 'PROJECT_MANAGER' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' ?>">
                                                    <?= $member['role'] === 'PROJECT_MANAGER' ? 'Manager' : 'Member' ?>
                                                </span>
                                                <button class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-4 bg-white rounded-lg">
                                        <p class="text-gray-500">No members assigned to this task yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Task Details Card -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Details</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                    <div class="flex items-center">
                                        <?php
                                            $statusColors = [
                                                'TODO' => 'bg-gray-100 text-gray-800',
                                                'IN_PROGRESS' => 'bg-yellow-100 text-yellow-800',
                                                'DONE' => 'bg-green-100 text-green-800'
                                            ];
                                            $dotColors = [
                                                'TODO' => 'bg-gray-400',
                                                'IN_PROGRESS' => 'bg-yellow-400',
                                                'DONE' => 'bg-green-400'
                                            ];
                                            $statusColor = $statusColors[$task['status']] ?? 'bg-gray-100 text-gray-800';
                                            $dotColor = $dotColors[$task['status']] ?? 'bg-gray-400';
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusColor ?>">
                                            <span class="w-2 h-2 rounded-full <?= $dotColor ?> mr-2"></span>
                                            <?= ucfirst(strtolower(str_replace('_', ' ', $task['status']))) ?>
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Due Date</label>
                                    <div class="text-sm text-gray-900">
                                        <i class="far fa-calendar-alt mr-2"></i>
                                        <?= $task['dueDate'] ?>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                                    <div class="flex items-center">
                                        <?php
                                            $priorityColors = [
                                                'LOW' => 'bg-green-100 text-green-800',
                                                'MEDIUM' => 'bg-yellow-100 text-yellow-800',
                                                'HIGH' => 'bg-red-100 text-red-800'
                                            ];
                                            $priorityColor = $priorityColors[$task['priority']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $priorityColor ?>">
                                            <i class="fas fa-flag mr-2"></i>
                                            <?= ucfirst(strtolower($task['priority'])) ?>
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-tag mr-2"></i>
                                            <?= $category['name'] ?>
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Project Manager</label>
                                    <div class="flex -space-x-2 overflow-hidden">
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name=<?= $_SESSION['username'] ?>" alt="<?= $_SESSION['username'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Timeline -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
                            <div class="space-y-4">
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Sarah updated the progress to 65%</p>
                                        <p class="text-xs text-gray-400">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">John completed the color palette task</p>
                                        <p class="text-xs text-gray-400">Yesterday at 4:30 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>