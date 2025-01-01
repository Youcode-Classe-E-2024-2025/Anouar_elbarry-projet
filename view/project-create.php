<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <form x-data="projectForm()" @submit.prevent="submitProject" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Create a New Project</h2>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Project Name</label>
                <input 
                    type="text" 
                    x-model="projectName" 
                    id="name" 
                    required 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter project name"
                >
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea 
                    x-model="projectDescription" 
                    id="description" 
                    rows="4" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Describe project objectives and details"
                ></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Project Visibility</label>
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        x-model="isPublic" 
                        id="isPublic" 
                        class="mr-2 leading-tight"
                    >
                    <label for="isPublic" class="text-sm">Public project (visible to all members)</label>
                </div>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <div class="flex">
                    <select 
                        x-model="projectCategory" 
                        id="category" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2"
                    >
                        <option value="">Select a category</option>
                        <option value="web">Web Development</option>
                        <option value="mobile">Mobile Application</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                        <option value="other">Other</option>
                    </select>
                    <button 
                        type="button" 
                        @click="showCustomCategoryModal = true"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                    >
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Create Project
                </button>
                <a href="project-manager-dashboard.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Custom Category Modal -->
        <div 
            x-show="showCustomCategoryModal" 
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-cloak
        >
            <div class="bg-white rounded-lg shadow-xl w-96 p-6">
                <h3 class="text-xl font-bold mb-4">Create Custom Category</h3>
                <form @submit.prevent="createCustomCategory">
                    <div class="mb-4">
                        <label for="customCategory" class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                        <input 
                            type="text" 
                            x-model="customCategoryName" 
                            id="customCategory" 
                            required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                            placeholder="Enter category name"
                        >
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button 
                            type="button" 
                            @click="showCustomCategoryModal = false"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function projectForm() {
            return {
                projectName: '',
                projectDescription: '',
                isPublic: false,
                projectCategory: '',
                showCustomCategoryModal: false,
                customCategoryName: '',
                userCategories: [],
                
                init() {
                    // Fetch user's custom categories on page load
                    this.fetchUserCategories();
                },

                fetchUserCategories() {
                    // Simulated AJAX call to fetch user's custom categories
                    fetch('get_user_categories.php', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.userCategories = data.categories;
                            // Dynamically add user categories to the select dropdown
                            const categorySelect = document.getElementById('category');
                            this.userCategories.forEach(category => {
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = category.name;
                                categorySelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching categories:', error);
                    });
                },

                createCustomCategory() {
                    if (!this.customCategoryName) {
                        alert('Please enter a category name.');
                        return;
                    }

                    // Send new category to server
                    fetch('create_category.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: this.customCategoryName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add new category to the select dropdown
                            const categorySelect = document.getElementById('category');
                            const option = document.createElement('option');
                            option.value = data.categoryId;
                            option.textContent = this.customCategoryName;
                            categorySelect.appendChild(option);

                            // Set the newly created category as selected
                            this.projectCategory = data.categoryId;

                            // Close the modal and reset input
                            this.showCustomCategoryModal = false;
                            this.customCategoryName = '';
                        } else {
                            alert('Error creating category: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                },
                
                submitProject() {
                    // Validate form data
                    if (!this.projectName || !this.projectDescription) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    // Prepare project data
                    const projectData = {
                        name: this.projectName,
                        description: this.projectDescription,
                        isPublic: this.isPublic,
                        category: this.projectCategory
                    };

                    // Send data to server via AJAX
                    fetch('create_project.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(projectData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to project dashboard or show success message
                            window.location.href = 'project-manager-dashboard.php';
                        } else {
                            alert('Error creating project: ' + data.message);
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
