<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <form x-data="taskForm()" @submit.prevent="submitTask" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Create a New Task</h2>
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Task Title</label>
                <input 
                    type="text" 
                    x-model="taskTitle" 
                    id="title" 
                    required 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter task title"
                >
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea 
                    x-model="taskDescription" 
                    id="description" 
                    rows="4" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Describe task details and objectives"
                ></textarea>
            </div>

            <div class="mb-4">
                <label for="dueDate" class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                <input 
                    type="text" 
                    x-model="taskDueDate" 
                    id="dueDate" 
                    x-ref="dueDatePicker"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Select due date"
                >
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                <select 
                    x-model="taskPriority" 
                    id="priority" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                    <option value="">Select priority</option>
                    <option value="LOW">Low</option>
                    <option value="MEDIUM">Medium</option>
                    <option value="HIGH">High</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <select 
                    x-model="taskCategory" 
                    id="category" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                >
                    <option value="">Select a category</option>
                    <option value="development">Development</option>
                    <option value="design">Design</option>
                    <option value="testing">Testing</option>
                    <option value="documentation">Documentation</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
                <input 
                    type="text" 
                    x-model="taskTags" 
                    id="tags" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter tags separated by commas"
                >
            </div>

            <div class="flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Create Task
                </button>
                <a href="project-manager-dashboard.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function taskForm() {
            return {
                taskTitle: '',
                taskDescription: '',
                taskDueDate: '',
                taskPriority: '',
                taskCategory: '',
                taskTags: '',

                init() {
                    // Initialize Flatpickr for date selection
                    flatpickr(this.$refs.dueDatePicker, {
                        dateFormat: 'Y-m-d',
                        minDate: 'today'
                    });
                },
                
                submitTask() {
                    // Validate form data
                    if (!this.taskTitle || !this.taskDescription || !this.taskDueDate || !this.taskPriority) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    // Prepare task data
                    const taskData = {
                        title: this.taskTitle,
                        description: this.taskDescription,
                        dueDate: this.taskDueDate,
                        priority: this.taskPriority,
                        category: this.taskCategory,
                        tags: this.taskTags.split(',').map(tag => tag.trim())
                    };

                    // Send data to server via AJAX
                    fetch('create_task.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(taskData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to project dashboard or show success message
                            window.location.href = 'project-manager-dashboard.php';
                        } else {
                            alert('Error creating task: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
            }
        }
    </script>
</body>
</html>
