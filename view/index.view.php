<?php 
session_start();
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";

// Initialize user
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $project_id  = $_GET['project_id'] ;
}
$project = $user->getProjectById($project_id);
$projectMembers = $user->getProjectMembers($project_id);
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
                        <span><i class="fas fa-tasks mr-2"></i>12 tasks</span>
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
                    <span class="text-sm text-gray-500">4 tasks</span>
                </div>
                <!-- Task Cards -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-800">Design User Interface</h3>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Medium</span>
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
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">High</span>
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
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">High</span>
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
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Low</span>
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
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Completed</span>
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
            <form class="space-y-6">
                <div class="grid grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Task Title</label>
                            <input type="text" placeholder="Enter task title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                            <textarea rows="3" placeholder="Enter task description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Due Date</label>
                                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Priority</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                                    <option value="development" data-color="purple">Development</option>
                                    <option value="design" data-color="blue">Design</option>
                                    <option value="marketing" data-color="green">Marketing</option>
                                    <option value="research" data-color="yellow">Research</option>
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
                                       placeholder="Type tag and press Enter (e.g., feature, bug, urgent)" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <input type="hidden" name="tags" id="tagsInput" value="">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-gray-700 mb-1 text-sm">Assign Team Members</label>
                            <div class="border rounded-lg p-3 max-h-48 overflow-y-auto">
                                <div class="flex items-center justify-between py-1.5 border-b">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff" 
                                             alt="John Doe" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium">John Doe</p>
                                            <p class="text-xs text-gray-500">john.doe@example.com</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="member_roles[1]" 
                                                   value="TEAM_MEMBER"
                                                   class="form-checkbox text-blue-600">
                                            <span class="text-xs text-gray-700">Add to team</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=0D8ABC&color=fff" 
                                             alt="Jane Smith" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium">Jane Smith</p>
                                            <p class="text-xs text-gray-500">jane.smith@example.com</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="member_roles[2]" 
                                                   value="TEAM_MEMBER"
                                                   class="form-checkbox text-blue-600">
                                            <span class="text-xs text-gray-700">Add to team</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=0D8ABC&color=fff" 
                                             alt="Mike Johnson" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium">Mike Johnson</p>
                                            <p class="text-xs text-gray-500">mike.johnson@example.com</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="member_roles[3]" 
                                                   value="TEAM_MEMBER"
                                                   class="form-checkbox text-blue-600">
                                            <span class="text-xs text-gray-700">Add to team</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=0D8ABC&color=fff" 
                                             alt="Sarah Wilson" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium">Sarah Wilson</p>
                                            <p class="text-xs text-gray-500">sarah.wilson@example.com</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="member_roles[4]" 
                                                   value="TEAM_MEMBER"
                                                   class="form-checkbox text-blue-600">
                                            <span class="text-xs text-gray-700">Add to team</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://ui-avatars.com/api/?name=Alex+Brown&background=0D8ABC&color=fff" 
                                             alt="Alex Brown" 
                                             class="w-6 h-6 rounded-full">
                                        <div>
                                            <p class="text-sm font-medium">Alex Brown</p>
                                            <p class="text-xs text-gray-500">alex.brown@example.com</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="member_roles[5]" 
                                                   value="TEAM_MEMBER"
                                                   class="form-checkbox text-blue-600">
                                            <span class="text-xs text-gray-700">Add to team</span>
                                        </label>
                                    </div>
                                </div>
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

        // Add new category
        function addNewCategory() {
            const input = document.getElementById('newCategory');
            const categoryText = input.value.trim();
            
            if (categoryText) {
                const select = document.getElementById('categorySelect');
                const colors = ['purple', 'blue', 'green', 'yellow'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                
                const option = document.createElement('option');
                option.value = categoryText.toLowerCase();
                option.textContent = categoryText;
                option.dataset.color = randomColor;
                
                select.appendChild(option);
                select.value = option.value; // Select the new option
                
                // Reset and hide the new category input
                input.value = '';
                toggleNewCategoryInput();
            }
        }

        // Handle Enter key press for category input
        document.getElementById('newCategory').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addNewCategory();
            }
        });

        // Handle Escape key press to cancel new category
        document.getElementById('newCategory').addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                toggleNewCategoryInput();
            }
        });

        // Tags handling
        const tagInput = document.getElementById('tagInput');
        const tagsContainer = document.getElementById('tagsContainer');
        const tagsInput = document.getElementById('tagsInput');
        let tags = new Set();

        // Add tag function
        function addTag(tagText) {
            tagText = tagText.trim().toLowerCase();
            if (tagText && !tags.has(tagText)) {
                tags.add(tagText);
                
                const tagElement = document.createElement('div');
                tagElement.className = 'inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm';
                tagElement.innerHTML = `
                    <span>#${tagText}</span>
                    <button type="button" onclick="removeTag('${tagText}')" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                tagsContainer.appendChild(tagElement);
                updateTagsInput();
            }
        }

        // Remove tag function
        function removeTag(tagText) {
            tags.delete(tagText);
            const tagElements = tagsContainer.children;
            for (let element of tagElements) {
                if (element.querySelector('span').textContent === `#${tagText}`) {
                    element.remove();
                    break;
                }
            }
            updateTagsInput();
        }

        // Update hidden input with all tags
        function updateTagsInput() {
            tagsInput.value = Array.from(tags).join(',');
        }

        // Handle tag input
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const tags = this.value.split(',');
                tags.forEach(tag => {
                    if (tag.trim()) {
                        addTag(tag);
                    }
                });
                this.value = '';
            }
        });

        // Handle paste event
        tagInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const tags = pastedText.split(/[,\n\r]/);
            tags.forEach(tag => {
                if (tag.trim()) {
                    addTag(tag);
                }
            });
        });

        // Store assigned members
        let assignedMembersList = new Set();

        // Assign member function
        function assignMember() {
            const select = document.getElementById('memberSelect');
            const memberId = select.value;
            const memberName = select.options[select.selectedIndex].text;
            
            if (memberId && !assignedMembersList.has(memberId)) {
                assignedMembersList.add(memberId);
                
                const assignedMembersDiv = document.getElementById('assignedMembers');
                const memberTag = document.createElement('div');
                memberTag.className = 'inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
                memberTag.innerHTML = `
                    <span>${memberName}</span>
                    <button type="button" onclick="removeMember('${memberId}')" class="text-blue-600 hover:text-blue-800 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                assignedMembersDiv.appendChild(memberTag);
                updateAssignedMembersInput();
                select.value = ''; // Reset select
            }
        }

        // Remove member function
        function removeMember(memberId) {
            assignedMembersList.delete(memberId);
            const assignedMembersDiv = document.getElementById('assignedMembers');
            const memberTags = assignedMembersDiv.children;
            
            for (let tag of memberTags) {
                if (tag.querySelector(`button[onclick="removeMember('${memberId}')"]`)) {
                    tag.remove();
                    break;
                }
            }
            
            updateAssignedMembersInput();
        }

        // Update hidden input with assigned member IDs
        function updateAssignedMembersInput() {
            const input = document.getElementById('assignedMembersInput');
            input.value = Array.from(assignedMembersList).join(',');
        }

        // Handle Enter key press for member selection
        document.getElementById('memberSelect').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                assignMember();
            }
        });
    </script>
</body>
</html>