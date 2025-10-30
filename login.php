<?php
session_start();
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM admin_accounts WHERE username='$username'");
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌿 Herbal Admin Login - Herbal Wonders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        herbal: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(34, 197, 94, 0.3)' },
                            '50%': { boxShadow: '0 0 40px rgba(34, 197, 94, 0.6)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .glass-strong {
            background: rgba(22, 163, 74, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .btn-herbal {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            transition: all 0.3s ease;
        }

        .btn-herbal:hover {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.4);
        }

        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3); }
            50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.6); }
        }

        .parallax-leaf {
            opacity: 0.08;
            pointer-events: none;
            position: fixed;
            z-index: 0;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        input::placeholder {
            color: rgba(148, 163, 184, 0.7);
        }

        input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Animated Background Elements -->
    <i class="fas fa-leaf parallax-leaf text-8xl text-herbal-400" style="top: 10%; left: 5%; animation: float 8s ease-in-out infinite;"></i>
    <i class="fas fa-spa parallax-leaf text-6xl text-herbal-500" style="top: 60%; right: 10%; animation: float 10s ease-in-out infinite 1s;"></i>
    <i class="fas fa-seedling parallax-leaf text-7xl text-herbal-300" style="bottom: 20%; left: 15%; animation: float 12s ease-in-out infinite 2s;"></i>

    <!-- Main Container -->
    <div class="relative z-10 w-full px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto items-center">
            
            <!-- Left Side - Info Section -->
            <div class="hidden lg:flex flex-col justify-center space-y-8">
                <div class="fade-in">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center pulse-glow">
                            <i class="fas fa-leaf text-white text-2xl"></i>
                        </div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                            HERBAL WONDERS
                        </h1>
                    </div>
                    <p class="text-gray-400 text-lg">Natural Healing Database</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-herbal-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-shield-alt text-herbal-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Secure Admin Access</h3>
                            <p class="text-gray-400">Manage herbs with enhanced security and full database control.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-herbal-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-database text-herbal-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Complete Control</h3>
                            <p class="text-gray-400">Add, edit, and manage medicinal herbs in our comprehensive database.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-herbal-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-chart-line text-herbal-400 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Analytics & Insights</h3>
                            <p class="text-gray-400">Track usage and manage content with powerful admin tools.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="fade-in">
                <div class="glass-effect rounded-3xl p-8 md:p-12 w-full max-w-md mx-auto">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">Admin Login</h2>
                        <p class="text-gray-400">Enter your credentials to access the admin panel</p>
                    </div>

                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg flex items-center space-x-3 shake">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                            <span class="text-red-200 font-medium"><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" class="space-y-6">
                        <!-- Username Input -->
                        <div class="relative">
                            <label class="block text-gray-300 text-sm font-medium mb-2">Username</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-4 text-herbal-400"></i>
                                <input 
                                    type="text" 
                                    name="username" 
                                    placeholder="Enter your username" 
                                    required
                                    class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:border-herbal-400 focus:bg-white/15 transition-all duration-300"
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-4 text-herbal-400"></i>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Enter your password" 
                                    required
                                    class="w-full pl-12 pr-12 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:border-herbal-400 focus:bg-white/15 transition-all duration-300"
                                >
                                <button 
                                    type="button" 
                                    id="togglePassword" 
                                    class="absolute right-4 top-4 text-herbal-400 hover:text-herbal-300 transition-colors"
                                    title="Toggle password visibility"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Show Password Checkbox -->
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                id="showPassword" 
                                class="w-5 h-5 rounded border-white/30 bg-white/10 text-herbal-500 focus:ring-herbal-500 cursor-pointer"
                            >
                            <span class="text-gray-300 text-sm group-hover:text-white transition-colors">Show Password</span>
                        </label>

                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="w-full btn-herbal text-white font-bold py-3 rounded-xl text-lg flex items-center justify-center space-x-2 mt-8"
                        >
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login to Admin Panel</span>
                        </button>

                        <!-- Back Link -->
                        <div class="text-center pt-4">
                            <a 
                                href="main.php" 
                                class="text-gray-400 hover:text-herbal-300 transition-colors text-sm font-medium flex items-center justify-center space-x-2"
                            >
                                <i class="fas fa-arrow-left"></i>
                                <span>Go Back to Main Page</span>
                            </a>
                        </div>
                    </form>

                    <!-- Footer Info -->
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <p class="text-center text-gray-500 text-xs">
                            <i class="fas fa-lock text-herbal-400 mr-1"></i>
                            Your login is secure and encrypted
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility with checkbox
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('showPassword');
        const togglePasswordBtn = document.getElementById('togglePassword');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
            updatePasswordIcon();
        });

        // Toggle password visibility with eye icon
        togglePasswordBtn.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            showPasswordCheckbox.checked = !isPassword;
            updatePasswordIcon();
        });

        function updatePasswordIcon() {
            const icon = document.querySelector('#togglePassword i');
            const isPassword = passwordInput.type === 'password';
            icon.classList.toggle('fa-eye', isPassword);
            icon.classList.toggle('fa-eye-slash', !isPassword);
        }

        // Form validation feedback
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const username = document.querySelector('input[name="username"]');
            const password = document.querySelector('input[name="password"]');

            if (!username.value.trim()) {
                e.preventDefault();
                username.classList.add('shake');
                setTimeout(() => username.classList.remove('shake'), 500);
                return;
            }

            if (!password.value.trim()) {
                e.preventDefault();
                password.classList.add('shake');
                setTimeout(() => password.classList.remove('shake'), 500);
                return;
            }
        });

        // Input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-herbal-400/50');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-herbal-400/50');
            });
        });

        // Initialize password icon
        updatePasswordIcon();
    </script>

</body>
</html>