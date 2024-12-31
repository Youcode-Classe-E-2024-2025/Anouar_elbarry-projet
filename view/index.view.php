<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flow - Intuitive Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a5568;
            --secondary-color: #718096;
            --accent-color: #4299e1;
            --background-color: #f7fafc;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #e6eaf4 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .task-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05), 0 6px 6px rgba(0,0,0,0.05);
        }

        .column-header {
            background: linear-gradient(90deg, var(--accent-color), #6a89cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .drag-over {
            border: 2px dashed var(--accent-color);
            background-color: rgba(66, 153, 225, 0.1);
        }

        .tag {
            background: linear-gradient(145deg, #e6e9f0, #eef1f5);
            box-shadow: 3px 3px 6px #b8b9be, -3px -3px 6px #ffffff;
        }
    </style>
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

    <!-- Footer -->
    <footer class="glass-morphism p-4 mt-auto">
        <div class="container mx-auto text-center text-gray-600">
            <p>&copy; 2024 Flow. Streamline Your Productivity.</p>
        </div>
    </footer>

    <script>
        // Drag and Drop Functionality
        function initDragAndDrop() {
            const taskContainers = document.querySelectorAll('.task-column > div:last-child');

            taskContainers.forEach(container => {
                container.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    container.classList.add('drag-over');
                });

                container.addEventListener('dragleave', () => {
                    container.classList.remove('drag-over');
                });

                container.addEventListener('drop', (e) => {
                    e.preventDefault();
                    container.classList.remove('drag-over');
                    
                    const draggedTask = document.querySelector('.dragging');
                    container.appendChild(draggedTask);
                    draggedTask.classList.remove('dragging');

                    updateColumnCounts();
                });
            });
        }

        // Task Creation Function
        function createTaskElement(taskData) {
            const newTask = document.createElement('div');
            newTask.className = 'task-card glass-morphism p-4 rounded-lg';
            newTask.draggable = true;
            newTask.setAttribute('data-category', taskData.category);

            // Drag and Drop Event Listeners
            newTask.addEventListener('dragstart', (e) => {
                newTask.classList.add('dragging');
                e.dataTransfer.setData('text/plain', '');
            });

            newTask.addEventListener('dragend', () => {
                newTask.classList.remove('dragging');
            });

            // Generate tags HTML
            const tagsHTML = taskData.tags 
                ? taskData.tags.split(',').map(tag => {
                    const tagColors = {
                        'basic': 'bg-gray-200 text-gray-800',
                        'bug': 'bg-red-200 text-red-800',
                        'feature': 'bg-green-200 text-green-800'
                    };
                    const colorClass = tagColors[tag] || 'bg-blue-200 text-blue-800';
                    return `<span class="${colorClass} text-xs px-2 py-1 rounded-full mr-1">${tag}</span>`;
                }).join('')
                : '';

            newTask.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-800 flex-grow">${taskData.title}</h3>
                    <button class="delete-task text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="text-xs text-gray-600 flex justify-between items-center">
                    <span>${new Date().toLocaleDateString()}</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                        ${taskData.category ? taskData.category.charAt(0).toUpperCase() + taskData.category.slice(1) : 'Uncategorized'}
                    </span>
                </div>
                <div class="task-tags mt-2 flex flex-wrap gap-1">
                    ${tagsHTML}
                </div>
            `;

            // Delete Task Event Listener
            const deleteButton = newTask.querySelector('.delete-task');
            deleteButton.addEventListener('click', () => {
                newTask.remove();
                updateColumnCounts();
            });

            return newTask;
        }

        // Update Column Counts
        function updateColumnCounts() {
            const columns = [
                { id: 'todo-tasks', countId: 'todoCount' },
                { id: 'progress-tasks', countId: 'progressCount' },
                { id: 'done-tasks', countId: 'doneCount' }
            ];

            columns.forEach(column => {
                const taskContainer = document.getElementById(column.id);
                const countElement = document.getElementById(column.countId);
                countElement.textContent = taskContainer.children.length;
            });
        }

        // Add Task Modal Logic
        const addTaskBtn = document.getElementById('addTaskBtn');
        const addTaskModal = document.getElementById('addTaskModal');
        const newTaskForm = document.getElementById('newTaskForm');
        const cancelTaskBtn = document.getElementById('cancelTaskBtn');

        addTaskBtn.addEventListener('click', () => {
            addTaskModal.classList.remove('hidden');
        });

        cancelTaskBtn.addEventListener('click', () => {
            addTaskModal.classList.add('hidden');
        });

        newTaskForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const taskTitle = document.getElementById('taskTitle').value.trim();
            const taskCategory = document.getElementById('taskCategory').value;
            const taskTags = Array.from(document.getElementById('taskTags').selectedOptions).map(option => option.value);

            if (taskTitle) {
                const newTask = createTaskElement({
                    title: taskTitle,
                    category: taskCategory,
                    tags: taskTags.join(',')
                });

                document.getElementById('todo-tasks').appendChild(newTask);
                
                newTaskForm.reset();
                addTaskModal.classList.add('hidden');

                updateColumnCounts();
            }
        });

        // Theme Toggle (Basic Implementation)
        const themeToggle = document.getElementById('themeToggle');
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            themeToggle.innerHTML = document.body.classList.contains('dark') 
                ? '<i class="fas fa-sun"></i>' 
                : '<i class="fas fa-moon"></i>';
        });

        // Initialize Drag and Drop on Page Load
        initDragAndDrop();
    </script>
</body>
</html>