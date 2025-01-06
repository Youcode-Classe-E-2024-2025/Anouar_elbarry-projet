<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Role</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="container max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-blue-600 mb-4">Choose Your Role</h1>
            <p class="text-gray-600">Select the role that best matches your responsibilities</p>
        </div>

        <form action="../controller/role_selection.php" method="POST">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Team Member Card -->
                <div class="relative">
                    <input type="radio" name="role" id="team_member" value="TEAM_MEMBER" class="hidden peer" required>
                    <label for="team_member" class="block p-6 bg-white rounded-2xl shadow-sm border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:shadow-md hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-3xl text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Team Member</h3>
                            <p class="text-gray-600 mb-4">
                                Join projects, collaborate with team members, and contribute to project success.
                            </p>
                            <ul class="text-left text-sm text-gray-600 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Collaborate on tasks
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Track progress
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Share updates
                                </li>
                            </ul>
                        </div>
                    </label>
                </div>

                <!-- Project Manager Card -->
                <div class="relative">
                    <input type="radio" name="role" id="project_manager" value="PROJECT_MANAGER" class="hidden peer">
                    <label for="project_manager" class="block p-6 bg-white rounded-2xl shadow-sm border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:shadow-md hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-3xl text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Project Manager</h3>
                            <p class="text-gray-600 mb-4">
                                Lead projects, manage teams, and ensure project objectives are met.
                            </p>
                            <ul class="text-left text-sm text-gray-600 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Create & assign tasks
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Monitor team progress
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Generate reports
                                </li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>

            <div class="text-center mt-8">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                    Continue
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
