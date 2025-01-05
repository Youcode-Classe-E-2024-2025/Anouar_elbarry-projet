<?php
if (!isset($user)) {
    require_once __DIR__ . "/../../controller/classes/user.php";
    $user = new User($_SESSION['username'], $_SESSION['email']);
    $user->setId($_SESSION['userid']);
}
?>
<div id="newProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 w-11/12 max-w-2xl">
        <h2 class="text-2xl font-bold mb-6">Create a New Project</h2>
        <form id="newProjectForm" method="POST" action="../controller/project.controller.php">
            <input type="hidden" name="creatProject" value="1">
            
            <!-- Project Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-2">Project Name</label>
                    <input type="text" name="project_name" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Description</label>
                    <textarea name="project_description" class="w-full border rounded-lg px-3 py-2" rows="4" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="isPublic" value="1" checked class="form-checkbox text-blue-600">
                        <span class="text-gray-700">Public Project</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1">Public projects are visible to all users</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Due Date</label>
                    <input type="date" name="dueDate" class="w-full border rounded-lg px-3 py-2" required>
                </div>
            </div>
            
            <!-- Team Members Section -->
            <div class="mt-6">
                <label class="block text-gray-700 mb-2">Assign Team Members</label>
                <div class="border rounded-lg p-4 max-h-60 overflow-y-auto">
                    <?php 
                    $allUsers = User::getUsers();
                    foreach($allUsers as $member): 
                        if($member['id'] != $_SESSION['userid']): // Don't show current user
                    ?>
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div class="flex items-center space-x-3">
                            <img src="https://ui-avatars.com/api/?name=<?= $member['username'] ?>&background=0D8ABC&color=fff" 
                                 alt="<?= $member['username'] ?>" 
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-medium"><?= $member['username'] ?></p>
                                <p class="text-sm text-gray-500"><?= $member['email'] ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select name="member_roles[<?= $member['id'] ?>]" class="text-sm border rounded px-2 py-1">
                                <option value="">Not Assigned</option>
                                <option value="TEAM_MEMBER">Team Member</option>
                                <option value="PROJECT_MANAGER">Project Manager</option>
                            </select>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-4">
                <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="closeModal('newProjectModal')">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Project</button>
            </div>
        </form>
    </div>
</div>
