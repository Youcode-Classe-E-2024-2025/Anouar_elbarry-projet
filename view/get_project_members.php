<?php
session_start();
require_once __DIR__ . "/../controller/classes/user.php";
require_once __DIR__ . "/../controller/classes/project.php";

// Check if project_id is provided
if (!isset($_GET['project_id'])) {
    echo '<p class="text-gray-500 col-span-2">Invalid project ID</p>';
    exit;
}

// Initialize user
$user = new User($_SESSION['username'], $_SESSION['email']);
$user->setId($_SESSION['userid']);

// Get project members
$projectMembers = $user->getProjectMembers($_GET['project_id']);

if (!empty($projectMembers)) {
    foreach ($projectMembers as $member) {
        ?>
        <div class="flex items-center space-x-3 p-2 rounded-lg bg-gray-50">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['username']) ?>" 
                 alt="<?= htmlspecialchars($member['username']) ?>" 
                 class="w-10 h-10 rounded-full">
            <div>
                <div class="font-medium"><?= htmlspecialchars($member['username']) ?></div>
                <div class="text-sm text-gray-500"><?= htmlspecialchars($member['role']) ?></div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p class="text-gray-500 col-span-2">No team members assigned</p>';
}
?>
