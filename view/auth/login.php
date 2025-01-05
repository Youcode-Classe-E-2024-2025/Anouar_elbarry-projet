<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body 
    class="bg-gray-100 min-h-screen flex items-center justify-center"
>
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>
        
        <form  class="space-y-6" action="../../controller/login_process.php" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="email"
                        required 
                        placeholder="Enter your email"
                        class="block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                    >
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input 
                        required 
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                    >
                    <button 
                        type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                    >
                    </button>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors"
                >
                    Login
                </button>
                <div class="text-center mt-4">
                    <p class="text-gray-600">Don't have an account? 
                        <a href="register.php" class="text-blue-500 hover:text-blue-600 font-semibold transition-colors">
                            Register here
                        </a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</body>
</html>