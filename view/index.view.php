<?php 
session_start();
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";

// Initialize user
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
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
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Project Name</h1>
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
                    <p class="text-gray-600">Project Description goes here. This is a brief overview of what the project is about and its main objectives.</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span><i class="fas fa-calendar mr-2"></i>Due: Dec 31, 2023</span>
                        <span><i class="fas fa-users mr-2"></i>5 members</span>
                        <span><i class="fas fa-tasks mr-2"></i>12 tasks</span>
                    </div>
                </div>
                <div class="flex -space-x-2">
                    <img src="https://ui-avatars.com/api/?name=John+Doe" alt="Team Member" class="w-8 h-8 rounded-full border-2 border-white">
                    <img src="https://ui-avatars.com/api/?name=Jane+Smith" alt="Team Member" class="w-8 h-8 rounded-full border-2 border-white">
                    <img src="https://ui-avatars.com/api/?name=Bob+Johnson" alt="Team Member" class="w-8 h-8 rounded-full border-2 border-white">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs text-gray-600">+2</div>
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
                    <span class="text-sm text-gray-500">4 tasks</span>
                </div>
                <!-- Task Cards -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800">Design User Interface</h3>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Medium</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Create wireframes and mockups for the new dashboard interface.</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due Dec 20</span>
                            <img src="https://ui-avatars.com/api/?name=Jane+Smith" alt="Assigned to" class="w-6 h-6 rounded-full">
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800">Database Schema</h3>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">High</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Design and implement the database schema for user management.</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due Dec 15</span>
                            <img src="https://ui-avatars.com/api/?name=John+Doe" alt="Assigned to" class="w-6 h-6 rounded-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">In Progress</h2>
                    <span class="text-sm text-gray-500">3 tasks</span>
                </div>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800">API Integration</h3>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">High</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Integrate payment gateway API and implement webhook handlers.</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due Dec 18</span>
                            <img src="https://ui-avatars.com/api/?name=Bob+Johnson" alt="Assigned to" class="w-6 h-6 rounded-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Review</h2>
                    <span class="text-sm text-gray-500">2 tasks</span>
                </div>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800">User Authentication</h3>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Low</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Implement OAuth2 authentication flow and user session management.</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Due Dec 22</span>
                            <img src="https://ui-avatars.com/api/?name=Alice+Brown" alt="Assigned to" class="w-6 h-6 rounded-full">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Done</h2>
                    <span class="text-sm text-gray-500">3 tasks</span>
                </div>
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer opacity-75">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800 line-through">Project Setup</h3>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">Completed</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Initialize project repository and set up development environment.</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Completed Dec 10</span>
                            <img src="https://ui-avatars.com/api/?name=John+Doe" alt="Completed by" class="w-6 h-6 rounded-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Task Modal -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md m-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Add New Task</h2>
                <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="taskForm" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Task Title</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                        <input type="date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                        <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                    <select class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select team member</option>
                        <option value="1">John Doe</option>
                        <option value="2">Jane Smith</option>
                        <option value="3">Bob Johnson</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTaskModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        const taskModal = document.getElementById('taskModal');
        const addTaskBtn = document.getElementById('addTaskBtn');

        addTaskBtn.addEventListener('click', () => {
            taskModal.classList.remove('hidden');
            taskModal.classList.add('flex');
        });

        function closeTaskModal() {
            taskModal.classList.remove('flex');
            taskModal.classList.add('hidden');
        }

        // Close modal when clicking outside
        taskModal.addEventListener('click', (e) => {
            if (e.target === taskModal) {
                closeTaskModal();
            }
        });
    </script>
</body>
</html>