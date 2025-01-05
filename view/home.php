<?php
require_once __DIR__ . "/../controller/classes/project.php";
require_once __DIR__ . "/../controller/classes/configDB.php";
session_start();

// Function to show error message
function showError($message) {
    echo "<script>
        alert('$message');
        window.history.back();
    </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Protask</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .diagonal-box {
            position: relative;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            transform: skewY(-5deg);
        }
        .diagonal-content {
            transform: skewY(5deg);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 font-[Poppins]">
    <!-- Hero Section -->
    <header class="relative min-h-screen bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="top: -10%; left: -10%"></div>
            <div class="absolute w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="top: 50%; right: -10%"></div>
        </div>
        
        <!-- Navigation -->
        <nav class="relative z-10 glass-effect">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-bold">Protask</div>
                    <ul class="flex space-x-8">
                        <li><a class="hover:text-pink-300 transition-colors" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a class="hover:text-pink-300 transition-colors" href="#"><i class="fas fa-project-diagram"></i> Projects</a></li>
                        <li><a class="hover:text-pink-300 transition-colors" href="#"><i class="fas fa-info-circle"></i> About</a></li>
                        <li><a class="hover:text-pink-300 transition-colors" href="#"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-6 py-32 text-center">
            <h1 class="text-6xl font-bold mb-6 animate-float">Welcome to Protask</h1>
            <p class="text-xl mb-12">Your platform for innovative projects and community collaboration</p>
            <div class="flex justify-center space-x-6">
                <?php if(isset($_SESSION['username'])) { 
                    if($_SESSION['role'] === 'project_manager') { ?>
                        <a href="dashboard.php" class="px-8 py-3 bg-pink-500 hover:bg-pink-600 rounded-full transition-colors">Create Project</a>
                    <?php } else { 
                        // If member tries to create project
                        if(isset($_GET['action']) && $_GET['action'] === 'create') {
                            showError("Only Project Managers can create projects!");
                        }
                    ?>
                        <a href="?action=create" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 rounded-full transition-colors" 
                           title="Only Project Managers can create projects">Create Project</a>
                    <?php } ?>
                    <a href="view/dashboard.php" class="px-8 py-3 bg-purple-500 hover:bg-purple-600 rounded-full transition-colors">Join Project</a>
                <?php } else { ?>
                    <a href="auth/login.php" class="px-8 py-3 bg-pink-500 hover:bg-pink-600 rounded-full transition-colors">Create Project</a>
                    <a href="auth/login.php" class="px-8 py-3 bg-purple-500 hover:bg-purple-600 rounded-full transition-colors">Join Project</a>
                <?php } ?>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-16">
        <!-- About Section -->
        <section id="about" class="mb-32">
            <div class="glass-effect rounded-2xl p-12">
                <h2 class="text-4xl font-bold text-center mb-8">About Protask</h2>
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <div class="bg-white bg-opacity-20 rounded-xl p-6">
                            <h3 class="text-2xl font-semibold mb-4">Our Mission</h3>
                            <p class="text-gray-700">We're dedicated to transforming the way teams collaborate and manage projects. Protask provides innovative tools for seamless project management and team coordination.</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-xl p-6">
                            <h3 class="text-2xl font-semibold mb-4">Why Choose Us</h3>
                            <ul class="list-disc list-inside text-gray-700 space-y-2">
                                <li>Intuitive project management tools</li>
                                <li>Real-time collaboration features</li>
                                <li>Secure and reliable platform</li>
                                <li>24/7 customer support</li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-xl p-6">
                        <h3 class="text-2xl font-semibold mb-4">Our Values</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-users text-3xl text-purple-500"></i>
                                <div>
                                    <h4 class="font-semibold">Community First</h4>
                                    <p class="text-gray-700">Building strong, collaborative communities</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-lightbulb text-3xl text-yellow-500"></i>
                                <div>
                                    <h4 class="font-semibold">Innovation</h4>
                                    <p class="text-gray-700">Constantly evolving and improving</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-shield-alt text-3xl text-green-500"></i>
                                <div>
                                    <h4 class="font-semibold">Trust & Security</h4>
                                    <p class="text-gray-700">Your data's safety is our priority</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="mb-32">
            <div class="glass-effect rounded-2xl p-12">
                <h2 class="text-4xl font-bold text-center mb-8">Get in Touch</h2>
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="bg-white bg-opacity-20 rounded-xl p-6">
                            <h3 class="text-2xl font-semibold mb-4">Contact Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-envelope text-2xl text-blue-500"></i>
                                    <p>support@protask.com</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-phone text-2xl text-green-500"></i>
                                    <p>+1 (555) 123-4567</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-map-marker-alt text-2xl text-red-500"></i>
                                    <p>123 Project Street, Innovation City</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-xl p-6">
                            <h3 class="text-2xl font-semibold mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-3xl text-blue-600 hover:text-blue-700"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="text-3xl text-blue-400 hover:text-blue-500"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-3xl text-pink-600 hover:text-pink-700"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="text-3xl text-blue-700 hover:text-blue-800"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-xl p-6">
                        <h3 class="text-2xl font-semibold mb-4">Send us a Message</h3>
                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition-colors">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section class="mb-32">
            <h2 class="text-4xl font-bold text-center mb-16">Featured Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $db = new Database();
                $projects = Project::getPublicProjects($db);
                foreach ($projects as $project) {
                ?>
                <div class="group">
                    <div class="glass-effect rounded-xl p-6 transform transition-all duration-500 hover:scale-105 hover:rotate-2">
                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($project['name']) ?></h3>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($project['description']) ?></p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-sm text-gray-500">Team Project</span>
                            <a href="view/auth/login.php">
                                <button class="bg-pink-500 text-white px-4 py-2 rounded-full hover:bg-pink-600 transition-colors">
                                    Request to Join
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="glass-effect rounded-2xl p-12 mb-32">
            <h2 class="text-4xl font-bold text-center mb-12">What People Say</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-6 rounded-xl bg-white bg-opacity-20">
                    <p class="italic mb-4">"Protask has transformed the way we collaborate on projects!"</p>
                    <p class="font-semibold">- Sarah Johnson</p>
                </div>
                <div class="p-6 rounded-xl bg-white bg-opacity-20">
                    <p class="italic mb-4">"A fantastic platform for innovation and community engagement!"</p>
                    <p class="font-semibold">- Michael Chen</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Protask. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
