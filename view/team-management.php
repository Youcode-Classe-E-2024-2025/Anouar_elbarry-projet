<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" x-data="teamManagement()">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Team Management</h2>
                <button 
                    @click="showInviteModal = true"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                >
                    <i class="fas fa-user-plus mr-2"></i>Invite Member
                </button>
            </div>

            <!-- Team Members List -->
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Role</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <template x-for="member in teamMembers" :key="member.id">
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img 
                                            :src="member.avatar" 
                                            :alt="member.name" 
                                            class="w-10 h-10 rounded-full mr-3"
                                        >
                                        <span x-text="member.name"></span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left" x-text="member.email"></td>
                                <td class="py-3 px-6 text-left">
                                    <span 
                                        x-text="member.role" 
                                        :class="{
                                            'bg-green-200 text-green-800': member.role === 'PROJECT_MANAGER',
                                            'bg-blue-200 text-blue-800': member.role === 'MEMBER'
                                        }"
                                        class="px-3 py-1 rounded-full text-xs"
                                    ></span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <button 
                                            @click="editMember(member)"
                                            class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            @click="removeMember(member)"
                                            class="w-4 transform hover:text-red-500 hover:scale-110"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invite Member Modal -->
        <div 
            x-show="showInviteModal" 
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-cloak
        >
            <div class="bg-white rounded-lg shadow-xl w-96 p-6">
                <h3 class="text-xl font-bold mb-4">Invite a Member</h3>
                <form @submit.prevent="inviteMember">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input 
                            type="email" 
                            x-model="newMemberEmail" 
                            id="email" 
                            required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                            placeholder="Enter member's email"
                        >
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                        <select 
                            x-model="newMemberRole" 
                            id="role" 
                            required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                        >
                            <option value="">Select a role</option>
                            <option value="MEMBER">Member</option>
                            <option value="PROJECT_MANAGER">Project Manager</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button 
                            type="button" 
                            @click="showInviteModal = false"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            Invite
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function teamManagement() {
            return {
                teamMembers: [
                    {
                        id: 1,
                        name: 'Marie Dupont',
                        email: 'marie.dupont@example.com',
                        role: 'PROJECT_MANAGER',
                        avatar: 'https://randomuser.me/api/portraits/women/50.jpg'
                    },
                    {
                        id: 2,
                        name: 'Pierre Martin',
                        email: 'pierre.martin@example.com',
                        role: 'MEMBER',
                        avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
                    }
                ],
                showInviteModal: false,
                newMemberEmail: '',
                newMemberRole: '',

                inviteMember() {
                    if (!this.newMemberEmail || !this.newMemberRole) {
                        alert('Please fill in all fields.');
                        return;
                    }

                    // Send invitation via AJAX
                    fetch('invite_team_member.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            email: this.newMemberEmail,
                            role: this.newMemberRole
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add new member to the list
                            this.teamMembers.push({
                                id: data.memberId,
                                name: data.name,
                                email: this.newMemberEmail,
                                role: this.newMemberRole,
                                avatar: 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name)
                            });

                            // Reset form and close modal
                            this.newMemberEmail = '';
                            this.newMemberRole = '';
                            this.showInviteModal = false;
                        } else {
                            alert('Error inviting member: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                },

                editMember(member) {
                    // Open edit modal or redirect to edit page
                    console.log('Edit member:', member);
                },

                removeMember(member) {
                    if (confirm(`Are you sure you want to remove ${member.name} from the team?`)) {
                        fetch('remove_team_member.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ memberId: member.id })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove member from the list
                                this.teamMembers = this.teamMembers.filter(m => m.id !== member.id);
                            } else {
                                alert('Error removing member: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                }
            }
        }
    </script>
</body>
</html>
