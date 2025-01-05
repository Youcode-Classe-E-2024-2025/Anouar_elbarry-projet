<?php
if (!isset($user)) {
    require_once __DIR__ . "/../../controller/classes/user.php";
    $user = new User($_SESSION['username'], $_SESSION['email']);
    $user->setId($_SESSION['userid']);
}
?>
<div id="newProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-11/12 max-w-2xl">
        <h2 class="text-xl font-bold mb-4">Create a New Project</h2>
        <form id="newProjectForm" method="POST" action="../controller/project.controller.php">
            <input type="hidden" name="newProjectForm" value="1">
            
            <!-- Project Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1 text-sm">Project Name</label>
                    <input type="text" name="project_name" class="w-full border rounded-lg px-3 py-1.5" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1 text-sm">Description</label>
                    <textarea name="project_description" class="w-full border rounded-lg px-3 py-1.5" rows="3" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-2 text-sm">
                        <input type="checkbox" name="isPublic" value="1" checked class="form-checkbox text-blue-600">
                        <span class="text-gray-700">Public Project</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-1 text-sm">Due Date</label>
                    <input type="date" name="dueDate" class="w-full border rounded-lg px-3 py-1.5" required>
                </div>
            </div>
            
            <!-- Team Members Section -->
            <div class="mt-4">
                <label class="block text-gray-700 mb-1 text-sm">Assign Team Members</label>
                <div class="border rounded-lg p-3 max-h-48 overflow-y-auto">
                    <?php 
                    $allUsers = User::getUsers();
                    foreach($allUsers as $member): 
                        if($member['id'] != $_SESSION['userid']): // Don't show current user
                    ?>
                    <div class="flex items-center justify-between py-1.5 border-b last:border-0">
                        <div class="flex items-center space-x-2">
                            <img src="https://ui-avatars.com/api/?name=<?= $member['username'] ?>&background=0D8ABC&color=fff" 
                                 alt="<?= $member['username'] ?>" 
                                 class="w-6 h-6 rounded-full">
                            <div>
                                <p class="text-sm font-medium"><?= $member['username'] ?></p>
                                <p class="text-xs text-gray-500"><?= $member['email'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" 
                                       name="member_roles[<?= $member['id'] ?>]" 
                                       value="TEAM_MEMBER"
                                       class="form-checkbox text-blue-600">
                                <span class="text-xs text-gray-700">Add to team</span>
                            </label>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm" onclick="closeModal('newProjectModal')">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Create Project</button>
            </div>
        </form>
    </div>
</div>
