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

// Team Member Avatar Generation
function generateTeamMemberAvatars(teamMembers) {
    const teamMemberColors = {
        'john': '#3B82F6',   // Blue
        'sarah': '#10B981',  // Green
        'mike': '#EF4444',   // Red
        'emma': '#8B5CF6',   // Purple
        'alex': '#F59E0B'    // Amber
    };

    return teamMembers.map(member => {
        const color = teamMemberColors[member] || '#6B7280'; // Default gray
        const initials = member.charAt(0).toUpperCase();
        return `
            <div 
                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" 
                style="background-color: ${color}"
                title="${member.charAt(0).toUpperCase() + member.slice(1)}"
            >
                ${initials}
            </div>
        `;
    }).join('');
}

// Task Creation Function
function createTaskElement(taskData) {
    const newTask = document.createElement('div');
    newTask.className = 'task-card glass-morphism p-4 rounded-lg relative';
    newTask.draggable = true;
    newTask.setAttribute('data-category', taskData.category);
    newTask.setAttribute('data-priority', taskData.priority);
    newTask.setAttribute('data-description', taskData.description || '');

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

    // Generate team member avatars HTML
    const teamMemberAvatarsHTML = taskData.teamMembers 
        ? `<div class="flex space-x-1 mt-2 task-team-members">
            ${generateTeamMemberAvatars(taskData.teamMembers)}
           </div>`
        : '';

    // Modify priority indicator generation
    const priorityIndicator = taskData.priority ? `
        <span class="absolute top-1/2 left-1 transform -translate-y-1/2 w-3 h-3 rounded-full ${
            taskData.priority === 'low' ? 'bg-green-500' :
            taskData.priority === 'medium' ? 'bg-yellow-500' :
            'bg-red-500'
        } animate-pulse" title="${taskData.priority.charAt(0).toUpperCase() + taskData.priority.slice(1)} Priority"></span>
    ` : '';

    newTask.innerHTML = `
        <div class="task-card-wrapper relative group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-purple-100 opacity-0 group-hover:opacity-50 transition-opacity duration-300 rounded-lg blur-lg"></div>
            
            <div class="task-card-content relative z-10 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 pl-5">
                ${priorityIndicator}
                <div class="task-card-header flex items-start justify-between mb-3 relative">
                    <div class="flex-grow">
                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1 flex items-center">
                            ${taskData.title}
                            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                ${taskData.category ? taskData.category.charAt(0).toUpperCase() + taskData.category.slice(1) : 'Uncategorized'}
                            </span>
                        </h3>
                    </div>
                    <div class="task-card-actions flex flex-col space-y-2">
                        <button class="task-details-btn text-gray-500 hover:text-blue-600 transition-colors transform hover:-translate-y-0.5">
                            <i class="fas fa-info-circle text-lg"></i>
                        </button>
                        <button class="delete-task text-red-500 hover:text-red-700 transition-colors transform hover:-translate-y-0.5">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <div class="task-card-meta grid grid-cols-2 gap-2 text-xs text-gray-600 mb-3">
                    <div class="task-date flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                        <span class="truncate">${new Date().toLocaleDateString()}</span>
                    </div>
                    <div class="task-tags flex flex-wrap gap-1 justify-end">
                        ${tagsHTML}
                    </div>
                </div>
                
                ${teamMemberAvatarsHTML ? `
                <div class="task-card-team flex justify-between items-center border-t border-gray-200 pt-2 mt-2">
                    <div class="flex -space-x-2 overflow-hidden">
                        ${teamMemberAvatarsHTML}
                    </div>
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-users text-gray-400"></i>
                        <span class="text-xs text-gray-500">
                            ${taskData.teamMembers.length} Member${taskData.teamMembers.length !== 1 ? 's' : ''}
                        </span>
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    // Details Button Event Listener
    const detailsButton = newTask.querySelector('.task-details-btn');
    detailsButton.addEventListener('click', () => {
        showTaskDetails(newTask);
    });

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

// Team Members Modal Logic
const selectTeamMembersBtn = document.getElementById('selectTeamMembersBtn');
const teamMembersModal = document.getElementById('teamMembersModal');
const cancelTeamMembersBtn = document.getElementById('cancelTeamMembersBtn');
const confirmTeamMembersBtn = document.getElementById('confirmTeamMembersBtn');
const selectedTeamMembersInput = document.getElementById('selectedTeamMembers');
const teamMemberCheckboxes = document.querySelectorAll('.team-member-checkbox');

// Open Team Members Modal
selectTeamMembersBtn.addEventListener('click', () => {
    teamMembersModal.classList.remove('hidden');
    
    // Reset previous selections
    teamMemberCheckboxes.forEach(checkbox => {
        checkbox.checked = selectedTeamMembersInput.value.split(',').includes(checkbox.value);
    });
});

// Cancel Team Members Selection
cancelTeamMembersBtn.addEventListener('click', () => {
    teamMembersModal.classList.add('hidden');
});

// Confirm Team Members Selection
confirmTeamMembersBtn.addEventListener('click', () => {
    // Collect selected team members
    const selectedMembers = Array.from(teamMemberCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);

    // Update hidden input
    selectedTeamMembersInput.value = selectedMembers.join(',');

    // Update button text
    if (selectedMembers.length > 0) {
        selectTeamMembersBtn.textContent = `${selectedMembers.length} Team Member${selectedMembers.length > 1 ? 's' : ''} Selected`;
    } else {
        selectTeamMembersBtn.textContent = 'Select Team Members';
    }

    // Close modal
    teamMembersModal.classList.add('hidden');
});

// Close modal when clicking outside
teamMembersModal.addEventListener('click', (e) => {
    if (e.target === teamMembersModal) {
        teamMembersModal.classList.add('hidden');
    }
});

newTaskForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const taskTitle = document.getElementById('taskTitle').value.trim();
    const taskDescription = document.getElementById('taskDescription').value.trim();
    const taskCategory = document.getElementById('taskCategory').value;
    const taskTags = Array.from(document.getElementById('taskTags').selectedOptions).map(option => option.value);
    const taskTeamMembers = selectedTeamMembersInput.value.split(',').filter(member => member);
    const taskPriority = document.querySelector('input[name="taskPriority"]:checked').value;

    if (taskTitle) {
        const newTask = createTaskElement({
            title: taskTitle,
            description: taskDescription,
            category: taskCategory,
            tags: taskTags.join(','),
            teamMembers: taskTeamMembers,
            priority: taskPriority
        });

        document.getElementById('todo-tasks').appendChild(newTask);
        
        newTaskForm.reset();
        selectedTeamMembersInput.value = '';
        selectTeamMembersBtn.textContent = 'Select Team Members';
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

// Task Details Modal
const taskDetailsModal = document.createElement('div');
taskDetailsModal.id = 'taskDetailsModal';
taskDetailsModal.className = 'fixed inset-0 bg-black bg-opacity-70 backdrop-blur-md flex items-center justify-center z-50 p-4 overflow-y-auto';
taskDetailsModal.innerHTML = `
    <div class="relative w-full max-w-7xl min-h-[85vh] max-h-[95vh] bg-white rounded-3xl shadow-2xl grid grid-cols-12 overflow-hidden animate-slide-in-up">
        <!-- Left Sidebar: Elegant Task Overview -->
        <div class="col-span-4 bg-gradient-to-br from-indigo-600 to-purple-700 text-white p-8 flex flex-col justify-between relative overflow-hidden">
            <!-- Dynamic Background Shapes -->
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute w-80 h-80 bg-white bg-opacity-10 rounded-full -top-20 -right-20 transform rotate-45 animate-pulse"></div>
                <div class="absolute w-64 h-64 bg-white bg-opacity-10 rounded-full -bottom-20 -left-20 transform -rotate-45 animate-pulse delay-500"></div>
            </div>
            
            <div class="relative z-10 space-y-8">
                <!-- Task Header -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-2 bg-white rounded-full mr-3 animate-ping"></div>
                        <span id="taskCategoryBadge" class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-white bg-opacity-20 uppercase tracking-wider"></span>
                    </div>
                    <h2 id="taskDetailsTitle" class="text-4xl font-black mb-3 tracking-tight leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200"></h2>
                </div>

                <!-- Tags Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-80 flex items-center border-b border-white border-opacity-20 pb-2">
                        <i class="fas fa-tags mr-3 opacity-70"></i>Tags
                    </h3>
                    <div id="taskTagsContainer" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Assigned Members -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 opacity-80 flex items-center border-b border-white border-opacity-20 pb-2">
                        <i class="fas fa-user-friends mr-3 opacity-70"></i>Assigned Members
                    </h3>
                    <div id="taskTeamMembersContainer" class="flex -space-x-3 mb-4"></div>
                    <div id="assignedMembersList" class="space-y-3 max-h-64 overflow-y-auto"></div>
                </div>
            </div>
            
            <!-- Created Date with Elegant Design -->
            <div class="absolute bottom-0 left-0 w-full p-8">
                <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-4 border border-white border-opacity-10">
                    <p id="taskCreatedDate" class="text-sm font-medium opacity-80 flex items-center">
                        <i class="fas fa-calendar-alt mr-3 opacity-70"></i>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content: Detailed Information -->
        <div class="col-span-8 bg-gray-50 p-10 overflow-y-auto flex flex-col">
            <div class="grid grid-cols-1 gap-8 flex-grow">
                <!-- Priority Section with Enhanced Styling -->
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-circle mr-3 text-blue-600 text-2xl"></i>
                        <h3 class="text-2xl font-semibold text-gray-800">Priority</h3>
                    </div>
                    <div id="taskPriorityBadge" class="inline-block px-6 py-3 rounded-full text-sm font-bold shadow-md transform transition hover:scale-105 hover:shadow-xl"></div>
                </div>
                
                <!-- Description with Advanced Styling -->
                <div class="flex-grow relative">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-align-left mr-3 text-green-600 text-2xl"></i>
                        <h3 class="text-2xl font-semibold text-gray-800">Description</h3>
                    </div>
                    <div 
                        id="taskDescriptionContainer" 
                        class="bg-white rounded-3xl shadow-lg p-8 text-gray-700 min-h-[250px] max-h-[500px] overflow-y-auto border-l-8 border-blue-500 hover:shadow-xl transition-all duration-300 prose prose-lg"
                    ></div>
                </div>
            </div>
            
            <!-- Action Buttons with Modern Design -->
            <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <button id="editTaskBtn" class="flex items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-7 py-3.5 rounded-lg hover:from-blue-600 hover:to-blue-700 transition transform hover:scale-105 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-3"></i>Edit Task
                </button>
                <button id="closeTaskDetailsBtn" class="flex items-center bg-gray-200 text-gray-700 px-7 py-3.5 rounded-lg hover:bg-gray-300 transition transform hover:scale-105 shadow-md hover:shadow-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
`;
document.body.appendChild(taskDetailsModal);

// Description Modal Enhancement
function formatDescription(description) {
    // If description is empty or undefined
    if (!description || description.trim() === '') {
        return `
            <div class="text-center text-gray-500 italic p-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                No description available for this task.
            </div>
        `;
    }

    // Basic markdown-like formatting
    const formattedDescription = description
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')  // Bold
        .replace(/\*(.*?)\*/g, '<em>$1</em>')  // Italic
        .replace(/__(.*?)__/g, '<u>$1</u>')  // Underline
        .replace(/\n/g, '<br>');  // Line breaks

    return `
        <div class="prose prose-sm max-w-full">
            <div class="bg-white rounded-xl p-6 shadow-inner">
                <div class="text-gray-700 leading-relaxed">
                    ${formattedDescription}
                </div>
            </div>
        </div>
    `;
}

// Function to show task details
function showTaskDetails(taskElement) {
    try {
        // Defensive checks for element existence
        if (!taskElement) {
            console.error('Task element is undefined or null');
            return;
        }

        // Safe element selection with fallback values
        const titleElement = taskElement.querySelector('h3');
        const categoryElement = taskElement.querySelector('.bg-blue-100');
        const tagsElements = taskElement.querySelectorAll('.task-tags span');
        const teamMemberElements = taskElement.querySelectorAll('.task-team-members > div');

        const title = titleElement ? titleElement.textContent : 'Untitled Task';
        const category = categoryElement ? categoryElement.textContent : 'Uncategorized';
        const tags = Array.from(tagsElements).map(tag => tag.textContent);
        const teamMembers = Array.from(teamMemberElements).map(member => {
            const fullName = member.getAttribute('title') || member.textContent.trim();
            return { 
                initials: member.textContent.trim(), 
                fullName: fullName,
                color: member.style.backgroundColor || '#3B82F6' // Default blue if no color
            };
        });

        // Safe data attribute retrieval
        const description = taskElement.getAttribute('data-description') || 'No description available.';
        const priority = taskElement.getAttribute('data-priority') || 'low';

        // Verify modal elements exist before manipulation
        const modalElements = [
            'taskDetailsTitle', 
            'taskCategoryBadge', 
            'taskTagsContainer', 
            'taskTeamMembersContainer', 
            'assignedMembersList', 
            'taskCreatedDate', 
            'taskPriorityBadge', 
            'taskDescriptionContainer'
        ];

        const missingElements = modalElements.filter(id => !document.getElementById(id));
        if (missingElements.length > 0) {
            console.error('Missing modal elements:', missingElements);
            return;
        }

        // Update modal content with existing elements
        document.getElementById('taskDetailsTitle').textContent = title;
        
        // Category Badge
        const categoryBadge = document.getElementById('taskCategoryBadge');
        categoryBadge.textContent = category;
        categoryBadge.className = 'inline-block px-3 py-1 rounded-full text-sm font-semibold ' + 
            (category.toLowerCase() === 'work' ? 'bg-blue-200 text-blue-800' :
             category.toLowerCase() === 'personal' ? 'bg-green-200 text-green-800' :
             category.toLowerCase() === 'learning' ? 'bg-purple-200 text-purple-800' :
             'bg-gray-200 text-gray-800');

        // Tags
        const tagsContainer = document.getElementById('taskTagsContainer');
        tagsContainer.innerHTML = tags.length > 0 ? tags.map(tag => `
            <span class="px-3 py-1 rounded-full text-xs font-medium ${
                tag.toLowerCase() === 'basic' ? 'bg-gray-200 text-gray-800' :
                tag.toLowerCase() === 'bug' ? 'bg-red-200 text-red-800' :
                tag.toLowerCase() === 'feature' ? 'bg-green-200 text-green-800' :
                'bg-blue-200 text-blue-800'
            } hover:scale-105 transform transition">${tag}</span>
        `).join('') : '<span class="text-gray-500 italic">No tags</span>';

        // Team Members
        const teamMembersContainer = document.getElementById('taskTeamMembersContainer');
        const assignedMembersList = document.getElementById('assignedMembersList');
        
        if (teamMembers.length > 0) {
            teamMembersContainer.innerHTML = teamMembers.map(member => `
                <div 
                    class="w-12 h-12 rounded-full border-2 border-white flex items-center justify-center text-white font-bold hover:scale-110 transform transition"
                    style="background-color: ${member.color}"
                    title="${member.fullName}"
                >
                    ${member.initials}
                </div>
            `).join('');

            assignedMembersList.innerHTML = teamMembers.map(member => `
                <div class="flex items-center space-x-4 bg-white rounded-xl p-3 shadow-md hover:shadow-lg transition transform hover:scale-102">
                    <div 
                        class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold"
                        style="background-color: ${member.color}"
                    >
                        ${member.initials}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">${member.fullName}</p>
                        <p class="text-sm text-gray-500">Team Member</p>
                    </div>
                </div>
            `).join('');
        } else {
            teamMembersContainer.innerHTML = '<span class="text-gray-500 italic">No team members assigned</span>';
            assignedMembersList.innerHTML = '<span class="text-gray-500 italic">No team members assigned</span>';
        }

        // Priority
        const priorityBadge = document.getElementById('taskPriorityBadge');
        priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
        priorityBadge.className = 
            priority === 'low' ? 'bg-green-200 text-green-800' :
            priority === 'medium' ? 'bg-yellow-200 text-yellow-800' :
            'bg-red-200 text-red-800';

        // Description with enhanced formatting
        const descriptionContainer = document.getElementById('taskDescriptionContainer');
        const rawDescription = taskElement.getAttribute('data-description') || '';
        
        // Use the new formatting function
        descriptionContainer.innerHTML = formatDescription(rawDescription);

        // Optional: Add copy to clipboard functionality
        const copyDescriptionBtn = document.createElement('button');
        copyDescriptionBtn.innerHTML = '<i class="fas fa-copy mr-2"></i>Copy Description';
        copyDescriptionBtn.className = 'absolute top-2 right-2 bg-blue-50 text-blue-600 px-3 py-1 rounded-md text-xs hover:bg-blue-100 transition';
        copyDescriptionBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(rawDescription).then(() => {
                copyDescriptionBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                setTimeout(() => {
                    copyDescriptionBtn.innerHTML = '<i class="fas fa-copy mr-2"></i>Copy Description';
                }, 2000);
            });
        });

        // Append copy button if description exists
        if (rawDescription.trim() !== '') {
            descriptionContainer.style.position = 'relative';
            descriptionContainer.appendChild(copyDescriptionBtn);
        }

        // Created Date
        const createdDateElement = document.getElementById('taskCreatedDate');
        const currentDate = new Date();
        createdDateElement.innerHTML = `<i class="fas fa-calendar-alt mr-3 opacity-70"></i>${currentDate.toLocaleDateString()} at ${currentDate.toLocaleTimeString()}`;

        // Show modal
        const taskDetailsModal = document.getElementById('taskDetailsModal');
        taskDetailsModal.classList.remove('hidden');

    } catch (error) {
        console.error('Error in showTaskDetails:', error);
        alert('An error occurred while displaying task details. Please try again.');
    }
};

// Close Task Details Modal
document.getElementById('closeTaskDetailsBtn').addEventListener('click', () => {
    document.getElementById('taskDetailsModal').classList.add('hidden');
});

// Close modal when clicking outside
document.getElementById('taskDetailsModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('taskDetailsModal')) {
        document.getElementById('taskDetailsModal').classList.add('hidden');
    }
});

// Edit Task Button (placeholder)
document.getElementById('editTaskBtn').addEventListener('click', () => {
    alert('Edit task functionality coming soon!');
});

// Initialize Drag and Drop on Page Load
initDragAndDrop();