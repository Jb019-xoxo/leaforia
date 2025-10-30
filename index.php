<?php
include 'db.php';

// Get statistics
$total_herbs = $conn->query("SELECT COUNT(*) as count FROM herbs")->fetch_assoc()['count'];
$featured_herbs = $conn->query("SELECT * FROM herbs ORDER BY RAND() LIMIT 3")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🌿 Herbal Wonders - Natural Healing Database</title>
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
                }
            }
        }
    }
</script>
<style>
    body {
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        min-height: 100vh;
    }
    
    .glass-card {
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
    
    .leaf-pattern {
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(22, 163, 74, 0.1) 0%, transparent 50%);
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-slide-left {
        animation: slideLeft 1s ease-out forwards;
    }
    
    @keyframes slideLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .animate-slide-right {
        animation: slideRight 1s ease-out forwards;
    }
    
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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

    .hero-gradient {
        background: linear-gradient(135deg, rgba(22, 163, 74, 0.2) 0%, rgba(34, 197, 94, 0.1) 100%);
    }

    .pulse-glow {
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3); }
        50% { box-shadow: 0 0 40px rgba(34, 197, 94, 0.6); }
    }

    ::-webkit-scrollbar {
        width: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #16a34a, #22c55e);
        border-radius: 5px;
    }

    .parallax-leaf {
        position: absolute;
        opacity: 0.1;
        pointer-events: none;
    }

    .mobile-menu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .mobile-menu.active {
        max-height: 400px;
    }

    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #chat-assistant {
  position: fixed;
  bottom: 25px;
  right: 25px;
  width: 360px;
  height: 500px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  z-index: 9999;
  font-family: 'Poppins', sans-serif;
}

.chat-box {
  flex: 1;
  padding: 10px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  color: black;
}

.chat-message {
  margin: 8px 0;
  padding: 10px 15px;
  border-radius: 10px;
  max-width: 80%;
  line-height: 1.4;
}

.chat-message.bot {
  background: #e8f5e9;
  align-self: flex-start;
}

.chat-message.user {
  background: #c8e6c9;
  align-self: flex-end;
}

.chat-input {
  display: flex;
  border-top: 1px solid #ccc;
  color: black;
}

.chat-input input {
  flex: 1;
  border: none;
  padding: 10px;
  font-size: 14px;
  outline: none;
}

.chat-input button {
  background: #2e7d32;
  color: white;
  border: none;
  padding: 0 16px;
  cursor: pointer;

}

</style>
</head>
<body class="text-gray-100 font-sans overflow-x-hidden">
    <!-- Parallax Leaves Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <i class="fas fa-leaf parallax-leaf text-8xl text-herbal-400" style="top: 10%; left: 5%; animation: float 8s ease-in-out infinite;"></i>
        <i class="fas fa-spa parallax-leaf text-6xl text-herbal-500" style="top: 60%; right: 10%; animation: float 10s ease-in-out infinite 1s;"></i>
        <i class="fas fa-seedling parallax-leaf text-7xl text-herbal-300" style="bottom: 20%; left: 15%; animation: float 12s ease-in-out infinite 2s;"></i>
    </div>

    <!-- Header with Glass Effect -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-card">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3 animate-float cursor-pointer" onclick="window.location.href='index.php'">
                    <div class="w-12 h-12 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center shadow-lg pulse-glow">
                        <i class="fas fa-leaf text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                            HERBAL WONDERS
                        </h1>
                        <p class="text-xs text-gray-400">Natural Healing Database</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-2">
                    <a href="index.php" class="px-4 py-2 glass-strong text-herbal-300 rounded-lg font-medium hover:bg-herbal-600 hover:text-white transition-all">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="main.php" class="px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
                        <i class="fas fa-search mr-2"></i>Explore Herbs
                    </a>
                    <a href="#about" class="px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
                        <i class="fas fa-shield-alt mr-2"></i>Safety Info
                    </a>
                    <a href="#about" class="px-4 py-2 btn-herbal text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-info-circle mr-2"></i>About Us
                    </a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button id="menuToggle" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <nav id="mobileMenu" class="mobile-menu md:hidden mt-4">
                <a href="index.php" class="block px-4 py-2 text-herbal-300 rounded-lg font-medium hover:bg-herbal-600 hover:text-white transition-all mb-2">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="main.php" class="block px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all mb-2">
                    <i class="fas fa-search mr-2"></i>Explore Herbs
                </a>
                <a href="#about" class="block px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all mb-2">
                    <i class="fas fa-info-circle mr-2"></i>About
                </a>
                <a href="login.php" class="block px-4 py-2 btn-herbal text-white rounded-lg font-medium">
                    <i class="fas fa-user-shield mr-2"></i>Admin
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-left z-10">
                    <div class="inline-block glass-strong px-4 py-2 rounded-full mb-6">
                        <span class="text-herbal-300 text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>Natural Medicine at Your Fingertips
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                        <span class="bg-gradient-to-r from-herbal-300 via-herbal-400 to-herbal-500 bg-clip-text text-transparent">
                            Discover Nature's
                        </span>
                        <br>
                        <span class="text-white">Healing Power</span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        Explore our comprehensive database of medicinal herbs, their uses, benefits, and precautions. 
                        Empowering you with ancient wisdom backed by natural remedies.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="main.php" class="px-8 py-4 btn-herbal text-white rounded-xl font-bold text-lg shadow-lg inline-flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            Explore Herbs
                        </a>
                        <button onclick="scrollToSection('about')" class="px-8 py-4 glass-card text-gray-300 rounded-xl font-bold text-lg hover:glass-strong transition-all inline-flex items-center">
                            <i class="fas fa-play-circle mr-2"></i>
                            Learn More
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-12">
                        <div class="glass-card rounded-xl p-4 text-center hover-lift">
                            <div class="text-3xl font-bold text-herbal-400 counter" data-target="<?php echo $total_herbs; ?>">0</div>
                            <div class="text-sm text-gray-400 mt-1">Herbs</div>
                        </div>
                        <div class="glass-card rounded-xl p-4 text-center hover-lift">
                            <div class="text-3xl font-bold text-blue-400">100%</div>
                            <div class="text-sm text-gray-400 mt-1">Natural</div>
                        </div>
                        <div class="glass-card rounded-xl p-4 text-center hover-lift">
                            <div class="text-3xl font-bold text-purple-400">24/7</div>
                            <div class="text-sm text-gray-400 mt-1">Access</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Floating Card -->
                <div class="animate-slide-right relative z-10">
                    <div class="glass-card rounded-3xl p-8 leaf-pattern hover-lift">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-herbal-300">
                                <i class="fas fa-seedling mr-2"></i>Featured Herbs
                            </h3>
                            <span class="glass-strong px-3 py-1 rounded-full text-xs text-herbal-300">
                                Updated Daily
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            <?php foreach($featured_herbs as $herb): ?>
                            <div class="glass-strong rounded-xl p-4 hover:bg-herbal-500/20 transition-all cursor-pointer herb-card" onclick="herbCardClick('main.php')">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-herbal-900/50 flex-shrink-0">
                                        <?php if(!empty($herb['image'])): ?>
                                            <img src="<?= htmlspecialchars($herb['image']) ?>" alt="<?= htmlspecialchars($herb['name']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-leaf text-2xl text-herbal-400/50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-white"><?= htmlspecialchars($herb['name']) ?></h4>
                                        <p class="text-xs text-gray-400 italic"><?= htmlspecialchars($herb['scientificname']) ?></p>
                                    </div>
                                    <i class="fas fa-chevron-right text-herbal-400"></i>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button onclick="window.location.href='main.php'" class="w-full mt-6 py-3 btn-herbal text-white rounded-lg font-medium">
                            <i class="fas fa-th mr-2"></i>View All Herbs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-6 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                    Why Choose Herbal Wonders?
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Your trusted companion for natural healing and herbal knowledge
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card rounded-2xl p-8 hover-lift animate-fade-in text-center feature-card">
                    <div class="w-20 h-20 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                        <i class="fas fa-database text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-herbal-300 mb-3">Comprehensive Database</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Access detailed information about hundreds of medicinal herbs with descriptions, uses, and benefits.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card rounded-2xl p-8 hover-lift animate-fade-in text-center feature-card" style="animation-delay: 0.1s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                        <i class="fas fa-search text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-300 mb-3">Smart Search</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Find herbs by name, disease, symptoms, or medicinal properties with our intelligent search system.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card rounded-2xl p-8 hover-lift animate-fade-in text-center feature-card" style="animation-delay: 0.2s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                        <i class="fas fa-shield-alt text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-300 mb-3">Safety Information</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Detailed precautions and contraindications to ensure safe and responsible herbal usage.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card rounded-2xl p-8 hover-lift animate-fade-in text-center feature-card" style="animation-delay: 0.3s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                        <i class="fas fa-video text-3xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-300 mb-3">Rich Media</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        High-quality images and educational videos to help you identify and learn about each herb.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 px-6 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="glass-card rounded-3xl p-12 leaf-pattern">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="animate-slide-left">
                        <h2 class="text-4xl font-bold mb-6 bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                            About Herbal Wonders
                        </h2>
                        <p class="text-gray-300 text-lg leading-relaxed mb-6">
                            Herbal Wonders is your comprehensive digital guide to traditional and medicinal herbs. 
                            Our mission is to preserve ancient healing wisdom while making it accessible to modern users.
                        </p>
                        <p class="text-gray-300 text-lg leading-relaxed mb-8">
                            Whether you're a healthcare practitioner, herbalist, student, or simply curious about natural remedies, 
                            our database provides reliable, well-researched information to support your journey into herbal medicine.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-herbal-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-herbal-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Evidence-Based Information</h4>
                                    <p class="text-gray-400 text-sm">All herbs are documented with traditional uses and scientific research.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-herbal-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-herbal-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Regular Updates</h4>
                                    <p class="text-gray-400 text-sm">Our database is constantly updated with new herbs and research findings.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-herbal-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-herbal-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">User-Friendly Interface</h4>
                                    <p class="text-gray-400 text-sm">Easy navigation and powerful search make finding information effortless.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="animate-slide-right">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="glass-strong rounded-xl p-6 text-center">
                                <i class="fas fa-leaf text-4xl text-herbal-400 mb-3"></i>
                                <div class="text-3xl font-bold text-white mb-1 counter" data-target="<?php echo $total_herbs; ?>">0</div>
                                <div class="text-sm text-gray-400">Medicinal Herbs</div>
                            </div>
                            <div class="glass-strong rounded-xl p-6 text-center">
                                <i class="fas fa-book text-4xl text-blue-400 mb-3"></i>
                                <div class="text-3xl font-bold text-white mb-1 counter" data-target="500">0</div>
                                <div class="text-sm text-gray-400">Remedies</div>
                            </div>
                            <div class="glass-strong rounded-xl p-6 text-center">
                                <i class="fas fa-globe text-4xl text-purple-400 mb-3"></i>
                                <div class="text-3xl font-bold text-white mb-1">Global</div>
                                <div class="text-sm text-gray-400">Coverage</div>
                            </div>
                            <div class="glass-strong rounded-xl p-6 text-center">
                                <i class="fas fa-users text-4xl text-amber-400 mb-3"></i>
                                <div class="text-3xl font-bold text-white mb-1 counter" data-target="10000">0</div>
                                <div class="text-sm text-gray-400">Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="glass-card rounded-3xl p-12 leaf-pattern hero-gradient">
                <div class="w-24 h-24 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                    <i class="fas fa-spa text-5xl text-white"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 text-white">
                    Ready to Explore?
                </h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Start your journey into the world of herbal medicine. Discover natural remedies that have been trusted for centuries.
                </p>
                <a href="main.php" class="inline-flex items-center px-10 py-5 btn-herbal text-white rounded-xl font-bold text-xl shadow-2xl">
                    <i class="fas fa-leaf mr-3"></i>
                    Browse Herbal Database
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 🌿 AI Chat Assistant -->
<div id="chat-assistant">
  <div class="chat-box" id="chat-box">
    <div class="chat-message bot">👋 Hi! I'm your Herbal Assistant. Describe your symptom, and I'll check what herbs might help.</div>
  </div>

  <div class="chat-input">
    <input type="text" id="user-input" placeholder="Type your symptom (e.g., I have a cough)...">
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

<script>
async function sendMessage() {
  const input = document.getElementById("user-input");
  const chatBox = document.getElementById("chat-box");
  const userMessage = input.value.trim();
  if (userMessage === "") return;

  // Show user message
  chatBox.innerHTML += `<div class="chat-message user">${userMessage}</div>`;
  input.value = "";
  chatBox.scrollTop = chatBox.scrollHeight;

  // Show typing indicator
  const typingMsg = document.createElement("div");
  typingMsg.classList.add("chat-message", "bot");
  typingMsg.innerHTML = "💬 Typing...";
  chatBox.appendChild(typingMsg);
  chatBox.scrollTop = chatBox.scrollHeight;

  try {
    // ✅ Send message to chat.php as JSON (matches your PHP)
    const res = await fetch("chat.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message: userMessage })
    });

    // Get and parse JSON response
    const data = await res.json();
    const replyText = data.reply || "⚠️ No response received.";

    // Remove typing indicator and show response
    chatBox.removeChild(typingMsg);
    chatBox.innerHTML += `<div class="chat-message bot">${replyText}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;

  } catch (err) {
    chatBox.removeChild(typingMsg);
    chatBox.innerHTML += `<div class="chat-message bot">⚠️ Sorry, I had trouble connecting to the assistant.</div>`;
    console.error(err);
  }
}
</script>





    <!-- Footer -->
    <footer class="py-12 px-6 glass-card mt-20 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About Column -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-leaf text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-herbal-300">HERBAL WONDERS</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Your trusted source for comprehensive herbal medicine information. 
                        Bridging ancient wisdom with modern accessibility.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 glass-strong rounded-full flex items-center justify-center text-herbal-400 hover:bg-herbal-500 hover:text-white transition-all social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 glass-strong rounded-full flex items-center justify-center text-herbal-400 hover:bg-herbal-500 hover:text-white transition-all social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 glass-strong rounded-full flex items-center justify-center text-herbal-400 hover:bg-herbal-500 hover:text-white transition-all social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 glass-strong rounded-full flex items-center justify-center text-herbal-400 hover:bg-herbal-500 hover:text-white transition-all social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold text-herbal-300 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-herbal-400 transition-all text-sm">Home</a></li>
                        <li><a href="main.php" class="text-gray-400 hover:text-herbal-400 transition-all text-sm">Browse Herbs</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-herbal-400 transition-all text-sm">About Us</a></li>
                        <li><a href="login.php" class="text-gray-400 hover:text-herbal-400 transition-all text-sm">Admin Portal</a></li>
                    </ul>
                </div>


            
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // ========== Mobile Menu Toggle ==========
        document.getElementById('menuToggle').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
            this.innerHTML = menu.classList.contains('active') 
                ? '<i class="fas fa-times text-2xl"></i>' 
                : '<i class="fas fa-bars text-2xl"></i>';
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('mobileMenu').classList.remove('active');
                document.getElementById('menuToggle').innerHTML = '<i class="fas fa-bars text-2xl"></i>';
            });
        });

        // ========== Smooth Scroll to Section ==========
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // ========== Counter Animation for Stats ==========
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            const speed = 200; // milliseconds

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / speed;
                let current = 0;

                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target + '+';
                    }
                };

                // Start animation when element is in view
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        updateCounter();
                        observer.unobserve(counter);
                    }
                });

                observer.observe(counter);
            });
        }

        // ========== Herb Card Ripple Effect ==========
        document.querySelectorAll('.herb-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        function herbCardClick(url) {
            window.location.href = url;
        }

        // ========== Parallax Effect ==========
        function parallaxEffect() {
            const leaves = document.querySelectorAll('.parallax-leaf');
            window.addEventListener('scroll', () => {
                leaves.forEach(leaf => {
                    let scrollPosition = window.scrollY;
                    leaf.style.transform = `translateY(${scrollPosition * 0.5}px)`;
                });
            });
        }

        // ========== Feature Card Stagger Animation ==========
        function animateFeatureCards() {
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        }

        // ========== Navbar Hide on Scroll ==========
        let lastScrollTop = 0;
        const header = document.querySelector('header');

        window.addEventListener('scroll', () => {
            let scrollTop = window.scrollY || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling DOWN
                header.style.transform = 'translateY(-100%)';
                header.style.transition = 'transform 0.3s ease-out';
            } else {
                // Scrolling UP
                header.style.transform = 'translateY(0)';
                header.style.transition = 'transform 0.3s ease-out';
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        // ========== Social Link Click Handler ==========
        document.querySelectorAll('.social-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                showNotification('Follow us on social media!', 'info');
            });
        });

        // ========== Notification System ==========
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification glass-card rounded-lg px-6 py-4 text-white`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-info-circle';
            const color = type === 'success' ? 'text-green-400' : 'text-blue-400';
            
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas ${icon} ${color}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideUp 0.3s ease-out reverse forwards';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ========== Button Click Feedback ==========
        document.querySelectorAll('.btn-herbal, .glass-card button').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Ripple effect
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const ripple = document.createElement('span');
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.width = '20px';
                ripple.style.height = '20px';
                ripple.style.background = 'rgba(255, 255, 255, 0.5)';
                ripple.style.borderRadius = '50%';
                ripple.style.animation = 'ripple 0.6s ease-out';
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // ========== Ripple Animation Keyframes ==========
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // ========== Page Load Animations ==========
        window.addEventListener('load', () => {
            animateCounters();
            animateFeatureCards();
            parallaxEffect();
            showNotification('Welcome to Herbal Wonders! 🌿', 'success');
        });

        // ========== Active Link Highlighting ==========
        function updateActiveLink() {
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            document.querySelectorAll('nav a').forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPage || (currentPage === '' && href === 'index.php')) {
                    link.classList.add('glass-strong', 'text-herbal-300');
                } else {
                    link.classList.remove('glass-strong', 'text-herbal-300');
                }
            });
        }

        // ========== Lazy Loading Images ==========
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.add('fade-in');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img').forEach(img => imageObserver.observe(img));
        }

        // ========== Keyboard Navigation ==========
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('mobileMenu').classList.remove('active');
            }
        });

        // Initialize
        updateActiveLink();

        
    </script>
</body>
</html>