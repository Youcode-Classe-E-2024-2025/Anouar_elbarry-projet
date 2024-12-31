<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flow - Intuitive Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Assets/src/index.css">
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="glass-morphism p-4 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-stream text-2xl text-gray-700"></i>
                <h1 class="text-2xl font-bold text-gray-800">Flow</h1>
            </div>
            <div class="flex space-x-4">
                <button id="addTaskBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-full hover:from-blue-600 hover:to-blue-700 transition-all">
                    <i class="fas fa-plus mr-2"></i>New Task
                </button>
                <button id="themeToggle" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-full hover:bg-gray-300 transition-all">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-6">
            <!-- Todo Column -->
            <div class="glass-morphism p-5 rounded-xl task-column" id="todo-column" data-column="todo">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-xl font-bold column-header flex items-center">
                        <i class="fas fa-list-ul mr-3 text-gray-600"></i>To Do
                    </h2>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs" id="todoCount">0</span>
                </div>
                <div class="space-y-4 min-h-[300px]" id="todo-tasks">
                    <!-- Tasks dynamically added here -->
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="glass-morphism p-5 rounded-xl task-column" id="progress-column" data-column="progress">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-xl font-bold column-header flex items-center">
                        <i class="fas fa-spinner mr-3 text-gray-600"></i>In Progress
                    </h2>
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs" id="progressCount">0</span>
                </div>
                <div class="space-y-4 min-h-[300px]" id="progress-tasks">
                    <!-- Tasks dynamically added here -->
                </div>
            </div>

            <!-- Done Column -->
            <div class="glass-morphism p-5 rounded-xl task-column" id="done-column" data-column="done">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-xl font-bold column-header flex items-center">
                        <i class="fas fa-check-circle mr-3 text-gray-600"></i>Completed
                    </h2>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs" id="doneCount">0</span>
                </div>
                <div class="space-y-4 min-h-[300px]" id="done-tasks">
                    <!-- Tasks dynamically added here -->
                </div>
            </div>
        </div>
    </main>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
        <div class="glass-morphism w-[500px] p-6 rounded-xl shadow-2xl">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                <i class="fas fa-plus-circle mr-2 text-blue-600"></i>Create New Task
            </h2>
            <form id="newTaskForm">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Task Title</label>
                    <input type="text" id="taskTitle" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <select id="taskCategory" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" selected>Select Category</option>
                        <option value="work">Work</option>
                        <option value="personal">Personal</option>
                        <option value="learning">Learning</option>
                        <option value="health">Health</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Team Members</label>
                    <button type="button" id="selectTeamMembersBtn" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-left">
                        Select Team Members
                    </button>
                    <input type="hidden" id="selectedTeamMembers" name="teamMembers" value="">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Tags</label>
                    <select id="taskTags"  
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="basic" selected>Basic</option>
                        <option value="bug">Bug</option>
                        <option value="feature">Feature</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <button type="button" id="cancelTaskBtn" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Team Members Modal -->
    <div id="teamMembersModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-96 p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Select Team Members</h2>
            <div class="space-y-3" id="teamMembersContainer">
                <div class="flex items-center">
                    <input type="checkbox" id="john" name="teamMember" value="john" class="mr-2 team-member-checkbox">
                    <label for="john" class="flex items-center">
                        <span class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-white" style="background-color: #3B82F6;">J</span>
                        John Doe
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="sarah" name="teamMember" value="sarah" class="mr-2 team-member-checkbox">
                    <label for="sarah" class="flex items-center">
                        <span class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-white" style="background-color: #10B981;">S</span>
                        Sarah Smith
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="mike" name="teamMember" value="mike" class="mr-2 team-member-checkbox">
                    <label for="mike" class="flex items-center">
                        <span class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-white" style="background-color: #EF4444;">M</span>
                        Mike Johnson
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="emma" name="teamMember" value="emma" class="mr-2 team-member-checkbox">
                    <label for="emma" class="flex items-center">
                        <span class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-white" style="background-color: #8B5CF6;">E</span>
                        Emma Brown
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="alex" name="teamMember" value="alex" class="mr-2 team-member-checkbox">
                    <label for="alex" class="flex items-center">
                        <span class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-white" style="background-color: #F59E0B;">A</span>
                        Alex Wilson
                    </label>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" id="cancelTeamMembersBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
                <button type="button" id="confirmTeamMembersBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="glass-morphism p-4 mt-auto">
        <div class="container mx-auto text-center text-gray-600">
            <p>&copy; 2024 Flow. Streamline Your Productivity.</p>
        </div>
    </footer>
    <script src="Assets/script/index.js"></script>
</body>
</html>