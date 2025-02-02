<?php 
require_once __DIR__ ."/../../controller/classes/user.php";
require_once __DIR__ ."/../../controller/classes/task.php";
require_once __DIR__ ."/../../controller/classes/configDB.php";
session_start();
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $project_id  = $_GET['project_id'] ;
    // $_SESSION['project_id']  = $project_id;
}
$db = new Database();
$IN_progresstasks = Task::getTaskByStatus($db,'IN_PROGRESS',$project_id);
$TODOtasks = Task::getTaskByStatus($db,'TODO',$project_id);
$DONEtasks = Task::getTaskByStatus($db,'DONE',$project_id);
$AllTasks = count($DONEtasks) + count($IN_progresstasks) + count($TODOtasks);

$project = $user->getProjectById($project_id);
$projectMembers = $user->getProjectMembers($project_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
        <!-- Success/Error Messages -->
        <?php if(isset($_SESSION["success"])): ?>
    <div id="successAlert" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?php echo $_SESSION["successD"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["successD"]); endif; ?>

    <?php if(isset($_SESSION["errorD"])): ?>
    <div id="errorAlert" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span><?php echo $_SESSION["errorD"]; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php unset($_SESSION["error"]); endif; ?>
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="../dashboard.php" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">Website Redesign Project</h1>
                    <div class="flex items-center space-x-4">
                        <span class="px-4 py-2 rounded-full text-base bg-blue-100 text-blue-800 font-semibold">Active</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Project Description -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h2 class="text-xl font-semibold mb-4">Description</h2>
                        <p class="text-gray-700 text-lg leading-relaxed">
                           <?=$project["description"]; ?>
                        </p>
                    </div>

                    <!-- Project Progress -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h2 class="text-xl font-semibold mb-6">Progress Overview</h2>
                        <div class="space-y-6">
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full" style="width: 65%"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600"><?= $AllTasks?></div>
                                    <div class="text-gray-600">Total Tasks</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600"><?= count($DONEtasks) ?></div>
                                    <div class="text-gray-600">Completed</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600"><?= count($IN_progresstasks) ?></div>
                                    <div class="text-gray-600">In Progress</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h2 class="text-xl font-semibold mb-6">Team Members</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Team Member Cards -->
                             <?php foreach( $projectMembers as $projectMember ): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <img src="https://ui-avatars.com/api/?name=<?= $projectMember["username"] ?>&size=64" 
                                         alt="John Doe" 
                                         class="w-16 h-16 rounded-full">
                                    <div>
                                        <div class="font-semibold text-lg"><?= $projectMember["username"] ?></div>
                                        <div class="text-blue-600"><?= $projectMember["role"] ?></div>
                                        <div class="text-gray-500 text-sm"><?= $projectMember["email"] ?></div>
                                    </div>
                                </div>
                                <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                                <?php  if ($projectMember["role"] === "TEAM_MEMBER" ):?>
                                <a href="./../../controller/project.controller.php?action=delet_member&member_id=<?= $projectMember["id"] ?>&project_id=<?= $project_id ?>" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-white border border-red-600 hover:bg-red-600 rounded-md transition-colors duration-300">
                                    <i class="fas fa-user-minus mr-1"></i>
                                    Remove
                                </a>
                                <?php endif ?>
                                <?php endif ?>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Project Details Card -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h2 class="text-xl font-semibold mb-6">Project Details</h2>
                        <div class="space-y-4">
                            <div>
                                <div class="text-gray-500 text-sm">Due Date</div>
                                <div class="text-gray-900 flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <?=$project["dueDate"]; ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-sm">Created On</div>
                                <div class="text-gray-900 flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <?=$project["createdAt"]; ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-sm">Project Lead</div>
                                <div class="text-gray-900 flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    <?= $_SESSION['username'] ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <div class="space-y-4">
                            <a href="./../index.view.php?project_id=<?= $project['id'] ?>" class="flex items-center justify-center w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-tasks mr-2"></i>View Tasks
                            </a>
                            <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                            <button class="flex items-center justify-center w-full px-6 py-3 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>Edit Project
                            </button>
                            <?php endif?>
                            <?php if($_SESSION['userRole'] == 'PROJECT_MANAGER'): ?>
                            <a href="./../../controller/project.controller.php?action=delet&project_id=<?= $project_id ?>" class="flex items-center justify-center w-full px-6 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Project
                            </a>
                            <?php endif?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>