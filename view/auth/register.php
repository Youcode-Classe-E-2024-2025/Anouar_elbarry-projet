<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gradient-to-br from-green-400 to-blue-500 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div x-data="registrationForm()" class="bg-white shadow-2xl rounded-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>
                
                <form action="../../controller/register_process.php" method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                x-model="username" 
                                required 
                                class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Choose a unique username"
                                @input="checkUsernameAvailability"
                                name="username"
                            >
                            <div x-show="usernameStatus" class="absolute right-0 top-0 mt-2 mr-3">
                                <span x-show="usernameStatus === 'available'" class="text-green-500">✓</span>
                                <span x-show="usernameStatus === 'taken'" class="text-red-500">✗</span>
                            </div>
                        </div>
                        <p x-show="usernameStatus === 'taken'" class="mt-2 text-sm text-red-500">
                            Username is already taken
                        </p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                name="email"
                                x-model="email" 
                                required 
                                class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="you@example.com"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                 name="password"
                                :type="showPassword ? 'text' : 'password'" 
                                x-model="password" 
                                required 
                                class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Create a strong password"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-500"
                            >
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <p x-show="password.length > 0" class="mt-2 text-sm" :class="{
                            'text-red-500': password.length < 8,
                            'text-green-500': password.length >= 8
                        }">
                            <span x-show="password.length < 8">Password must be at least 8 characters</span>
                            <span x-show="password.length >= 8">Strong password</span>
                        </p>
                    </div>

                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input 
                            type="password" 
                            x-model="confirmPassword" 
                            required 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Repeat your password"
                        >
                        <p x-show="password !== confirmPassword && confirmPassword.length > 0" class="mt-2 text-sm text-red-500">
                            Passwords do not match
                        </p>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            x-model="agreeTerms" 
                            id="terms" 
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            I agree to the 
                            <a href="#" class="text-green-600 hover:text-green-500">Terms and Conditions</a>
                        </label>
                    </div>

                    <div>
                        <button 
                            type="submit" 
                            :disabled="!agreeTerms || password !== confirmPassword || password.length < 8 || usernameStatus === 'taken'"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-8 py-4 bg-gray-50 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="login.php" class="font-medium text-green-600 hover:text-green-500">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function registrationForm() {
            return {
                username: '',
                email: '',
                password: '',
                confirmPassword: '',
                agreeTerms: false,
                showPassword: false,
                usernameStatus: null,

                checkUsernameAvailability() {
                    // Simulated username availability check
                    const reservedUsernames = ['admin', 'root', 'system'];
                    this.usernameStatus = reservedUsernames.includes(this.username.toLowerCase()) ? 'taken' : 'available';
                },

                submitRegistration() {
                    // Client-side validation
                    if (!this.agreeTerms) {
                        alert('Please agree to the terms and conditions');
                        return;
                    }

                    if (this.password !== this.confirmPassword) {
                        alert('Passwords do not match');
                        return;
                    }

                    if (this.password.length < 8) {
                        alert('Password must be at least 8 characters');
                        return;
                    }

                    // If all validations pass, submit the form
                    document.querySelector('form').submit();
                }
            };
        }
    </script>
</body>
</html>
