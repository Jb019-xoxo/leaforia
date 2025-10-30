<?php
session_start();

// Check if user is logged in (add your authentication logic here)
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: login.php');
//     exit();
// }

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herbal_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize result variable
$result = null;

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    // Search query
    $search_param = "%" . $conn->real_escape_string($search) . "%";
    $sql = "SELECT * FROM herbs WHERE is_archived = 0 AND (
        name LIKE ? OR 
        scientificname LIKE ? OR 
        description LIKE ? OR 
        characteristics LIKE ? OR 
        uses LIKE ?
    ) ORDER BY date_added DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query - get all non-archived herbs
    $sql = "SELECT * FROM herbs WHERE is_archived = 0 ORDER BY date_added DESC";
    $result = $conn->query($sql);
}

// Get counts for stats
$total_active = $conn->query("SELECT COUNT(*) as count FROM herbs WHERE is_archived = 0")->fetch_assoc()['count'];
$total_archived = $conn->query("SELECT COUNT(*) as count FROM herbs WHERE is_archived = 1")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌿 Herbal Wonders Admin</title>
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
                        },
                        earth: {
                            50: '#fdfaf5',
                            100: '#f8f2e8',
                            200: '#eddcc5',
                            300: '#dfc49f',
                            400: '#caa875',
                            500: '#b89155',
                            600: '#9d7544',
                            700: '#7f5d38',
                            800: '#6b4e33',
                            900: '#5c432d',
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
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        
        .search-glow:focus {
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
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
        
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body class="text-gray-100 font-sans">
    <!-- Header with Glass Effect -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-card">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3 animate-float">
                    <div class="w-12 h-12 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-leaf text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                            HERBAL WONDERS
                        </h1>
                        <p class="text-xs text-gray-400">Admin Dashboard</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-2">
                    <button onclick="openCreateModal()" class="px-4 py-2 btn-herbal text-white rounded-lg font-medium flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Herb</span>
                    </button>
                    <a href="admin.php" class="px-4 py-2 glass-strong text-herbal-300 rounded-lg font-medium hover:bg-herbal-600 hover:text-white transition-all">
                        <i class="fas fa-tasks mr-2"></i>Manage
                    </a>
                    <a href="archive.php" class="px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
                        <i class="fas fa-archive mr-2"></i>Archive
                    </a>
                    <a href="main.php" class="px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="logout.php" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg font-medium hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-300 hover:text-white">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-28 pb-20 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Search Section -->
            <div class="mb-8 animate-fade-in leaf-pattern rounded-2xl p-8 glass-card">
                <h2 class="text-3xl font-bold text-center mb-6 bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                    <i class="fas fa-search mr-3"></i>Discover Herbal Remedies
                </h2>
                <form method="GET" action="admin.php" class="flex gap-3 max-w-3xl mx-auto">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?php echo htmlspecialchars($search); ?>"
                        placeholder="Search by herb name, uses, characteristics..." 
                        class="flex-1 px-6 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none search-glow focus:border-herbal-400 transition-all"
                    >
                    <button type="submit" class="px-8 py-4 btn-herbal text-white rounded-xl font-semibold flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </button>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6 hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Active Herbs</p>
                            <p class="text-3xl font-bold text-herbal-400">
                                <?php echo $total_active; ?>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-herbal-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-leaf text-herbal-400 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card rounded-xl p-6 hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Search Results</p>
                            <p class="text-3xl font-bold text-blue-400">
                                <?php echo $result ? $result->num_rows : 0; ?>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-list text-blue-400 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card rounded-xl p-6 hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Archived</p>
                            <p class="text-3xl font-bold text-amber-400">
                                <?php echo $total_archived; ?>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-archive text-amber-400 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Herbs Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $data = json_encode($row); 
                    ?>
                        <div class="glass-card rounded-2xl overflow-hidden hover-lift animate-fade-in">
                            <div class="flex flex-col md:flex-row h-full">
                                <!-- Image Section -->
                                <div class="md:w-2/5 h-64 md:h-auto bg-gradient-to-br from-herbal-900/50 to-herbal-700/50 flex items-center justify-center relative overflow-hidden">
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="<?= htmlspecialchars($row['image']) ?>" 
                                             alt="<?= htmlspecialchars($row['name']) ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-black/20"></div>
                                        <i class="fas fa-seedling text-6xl text-herbal-400/30 relative z-10"></i>
                                    <?php endif; ?>
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-herbal-400 to-herbal-600"></div>
                                </div>
                                
                                <!-- Content Section -->
                                <div class="md:w-3/5 p-6 flex flex-col">
                                    <div class="mb-4">
                                        <h3 class="text-2xl font-bold text-herbal-300 mb-1">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </h3>
                                        <p class="text-sm text-gray-400 italic">
                                            <?= htmlspecialchars($row['scientificname']) ?>
                                        </p>
                                    </div>
                                    
                                    <div class="flex-1 mb-4">
                                        <p class="text-sm text-gray-300 line-clamp-4">
                                            <?= htmlspecialchars($row['uses']) ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="grid grid-cols-3 gap-2">
                                        <button onclick='openViewModal(<?= htmlspecialchars($data, ENT_QUOTES) ?>)' 
                                                class="px-4 py-2 bg-herbal-500/20 text-herbal-300 rounded-lg hover:bg-herbal-500 hover:text-white transition-all font-medium text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                        <button onclick='openEditModal(<?= htmlspecialchars($data, ENT_QUOTES) ?>)' 
                                                class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500 hover:text-white transition-all font-medium text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <a href="archive_herb.php?id=<?= $row['id'] ?>" 
                                           onclick="return confirm('Archive this herb?')"
                                           class="px-4 py-2 bg-amber-500/20 text-amber-300 rounded-lg hover:bg-amber-500 hover:text-white transition-all font-medium text-sm flex items-center justify-center">
                                            <i class="fas fa-archive mr-1"></i>Archive
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="col-span-2 glass-card rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 bg-herbal-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-seedling text-4xl text-herbal-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-300 mb-2">No herbs found</h3>
                        <p class="text-gray-400 mb-6">
                            <?php echo !empty($search) ? 'Try adjusting your search' : 'Add a new herb to get started'; ?>
                        </p>
                        <button onclick="openCreateModal()" class="px-6 py-3 btn-herbal text-white rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Add Your First Herb
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="glass-card rounded-3xl max-w-5xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
            <!-- Modal Header -->
            <div class="sticky top-0 glass-strong rounded-t-3xl p-6 flex items-center justify-between border-b border-herbal-500/30 z-10">
                <div>
                    <h2 class="text-3xl font-bold text-herbal-300" id="modalName">Herb Name</h2>
                    <p class="text-sm text-gray-400 italic" id="modalScientific">Scientific name</p>
                </div>
                <button onclick="closeModal('viewModal')" class="w-10 h-10 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded-full transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div id="modalImageContainer" class="rounded-xl overflow-hidden border border-white/10">
                        <img id="modalImage" src="" alt="Herb" class="w-full h-64 object-cover">
                    </div>
                    
                    <div class="glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-herbal-300 mb-3 flex items-center">
                            <i class="fas fa-list-ul mr-2"></i>Characteristics
                        </h3>
                        <p id="modalCharacteristics" class="text-gray-300 text-sm leading-relaxed">
                            No characteristics available
                        </p>
                    </div>
                    
                    <div id="modalVideoContainer" class="hidden rounded-xl overflow-hidden border border-white/10">
                        <video id="modalVideo" controls class="w-full">
                            <source src="" type="video/mp4">
                        </video>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-herbal-300 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>Description
                        </h3>
                        <p id="modalDescription" class="text-gray-300 text-sm leading-relaxed">
                            No description available
                        </p>
                    </div>
                    
                    <div class="glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-herbal-300 mb-3 flex items-center">
                            <i class="fas fa-capsules mr-2"></i>Medicinal Uses
                        </h3>
                        <p id="modalUses" class="text-gray-300 text-sm leading-relaxed">
                            No uses listed
                        </p>
                    </div>
                    
                    <div class="glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-red-400 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Precautions
                        </h3>
                        <p id="modalPrecautions" class="text-gray-300 text-sm leading-relaxed">
                            No precautions listed
                        </p>
                    </div>
                    
                    <button id="modalYoutubeBtn" class="hidden w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all">
                        <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="glass-card rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
            <div class="sticky top-0 glass-strong rounded-t-3xl p-6 flex items-center justify-between border-b border-herbal-500/30 z-10">
                <h2 class="text-2xl font-bold text-herbal-300">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Herb
                </h2>
                <button onclick="closeModal('createModal')" class="w-10 h-10 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded-full transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="create.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-leaf mr-2"></i>Herb Name
                        </label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-microscope mr-2"></i>Scientific Name
                        </label>
                        <input type="text" name="scientificname"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-herbal-300 font-medium mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description
                    </label>
                    <textarea name="description" required rows="4"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-list-ul mr-2"></i>Characteristics
                        </label>
                        <textarea name="characteristics" rows="4"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-capsules mr-2"></i>Medicinal Uses
                        </label>
                        <textarea name="uses" rows="4"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                    </div>
                </div>
                
                <div>
                    <label class="block text-herbal-300 font-medium mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Precautions
                    </label>
                    <textarea name="precautions" rows="3"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-image mr-2"></i>Upload Image
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-herbal-500/20 file:text-herbal-300 hover:file:bg-herbal-500/30 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-video mr-2"></i>Upload Video
                        </label>
                        <input type="file" name="video_path" accept="video/*"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-herbal-500/20 file:text-herbal-300 hover:file:bg-herbal-500/30 transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fab fa-youtube mr-2"></i>YouTube Link
                        </label>
                        <input type="url" name="youtube_link"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-user mr-2"></i>Video Credits
                        </label>
                        <input type="text" name="video_credits"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                </div>
                
                <button type="submit" class="w-full py-4 btn-herbal text-white rounded-xl font-bold text-lg shadow-lg">
                    <i class="fas fa-save mr-2"></i>Save Herb
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="glass-card rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
            <div class="sticky top-0 glass-strong rounded-t-3xl p-6 flex items-center justify-between border-b border-herbal-500/30 z-10">
                <h2 class="text-2xl font-bold text-herbal-300">
                    <i class="fas fa-edit mr-2"></i>Edit Herb
                </h2>
                <button onclick="closeModal('editModal')" class="w-10 h-10 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded-full transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="edit.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-leaf mr-2"></i>Herb Name
                        </label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-microscope mr-2"></i>Scientific Name
                        </label>
                        <input type="text" name="scientificname" id="edit_scientific"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-herbal-300 font-medium mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description
                    </label>
                    <textarea name="description" id="edit_description" required rows="4"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-list-ul mr-2"></i>Characteristics
                        </label>
                        <textarea name="characteristics" id="edit_characteristics" rows="4"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-capsules mr-2"></i>Medicinal Uses
                        </label>
                        <textarea name="uses" id="edit_uses" rows="4"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                    </div>
                </div>
                
                <div>
                    <label class="block text-herbal-300 font-medium mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Precautions
                    </label>
                    <textarea name="precautions" id="edit_precautions" rows="3"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all resize-none"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-image mr-2"></i>Upload New Image
                        </label>
                        <input type="file" name="image" id="edit_image" accept="image/*"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-herbal-500/20 file:text-herbal-300 hover:file:bg-herbal-500/30 transition-all">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image</p>
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-video mr-2"></i>Upload New Video
                        </label>
                        <input type="file" name="video_path" id="edit_video" accept="video/*"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-herbal-500/20 file:text-herbal-300 hover:file:bg-herbal-500/30 transition-all">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to keep current video</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fab fa-youtube mr-2"></i>YouTube Link
                        </label>
                        <input type="url" name="youtube_link" id="edit_youtube"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-herbal-300 font-medium mb-2">
                            <i class="fas fa-user mr-2"></i>Video Credits
                        </label>
                        <input type="text" name="video_credits" id="edit_credits"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-herbal-400 transition-all">
                    </div>
                </div>
                
                <button type="submit" class="w-full py-4 btn-herbal text-white rounded-xl font-bold text-lg shadow-lg">
                    <i class="fas fa-save mr-2"></i>Update Herb
                </button>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        function openViewModal(data) {
            const modal = document.getElementById('viewModal');
            
            if (data) {
                document.getElementById('modalName').textContent = data.name || 'Herb Name';
                document.getElementById('modalScientific').textContent = data.scientificname || '';
                document.getElementById('modalCharacteristics').textContent = data.characteristics || 'No characteristics available';
                document.getElementById('modalDescription').textContent = data.description || 'No description available';
                document.getElementById('modalUses').textContent = data.uses || 'No uses listed';
                document.getElementById('modalPrecautions').textContent = data.precautions || 'No precautions listed';

                const imgContainer = document.getElementById('modalImageContainer');
                const img = document.getElementById('modalImage');
                if (data.image && data.image.trim() !== "") {
                    img.src = data.image;
                    imgContainer.classList.remove('hidden');
                } else {
                    imgContainer.classList.add('hidden');
                }

                const videoContainer = document.getElementById('modalVideoContainer');
                const video = document.getElementById('modalVideo');
                const source = video.querySelector('source');
                if (data.video_path && data.video_path.trim() !== "") {
                    source.src = data.video_path;
                    video.load();
                    videoContainer.classList.remove('hidden');
                } else {
                    videoContainer.classList.add('hidden');
                }

                const ytBtn = document.getElementById('modalYoutubeBtn');
                if (data.youtube_link && data.youtube_link.trim() !== "") {
                    ytBtn.classList.remove('hidden');
                    ytBtn.onclick = function() {
                        window.open(data.youtube_link, '_blank');
                    };
                } else {
                    ytBtn.classList.add('hidden');
                }
            }
            
            modal.classList.remove('hidden');
        }

        function openEditModal(data) {
            const modal = document.getElementById('editModal');
            
            if (data) {
                document.getElementById('edit_id').value = data.id || '';
                document.getElementById('edit_name').value = data.name || '';
                document.getElementById('edit_scientific').value = data.scientificname || '';
                document.getElementById('edit_characteristics').value = data.characteristics || '';
                document.getElementById('edit_uses').value = data.uses || '';
                document.getElementById('edit_description').value = data.description || '';
                document.getElementById('edit_precautions').value = data.precautions || '';
                document.getElementById('edit_youtube').value = data.youtube_link || '';
                document.getElementById('edit_credits').value = data.video_credits || '';
            }
            
            modal.classList.remove('hidden');
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal-backdrop').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>