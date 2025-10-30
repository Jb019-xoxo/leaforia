<?php  
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Handle restore action
if (isset($_GET['restore_id'])) {
    $restore_id = intval($_GET['restore_id']);
    $sql = "UPDATE herbs SET is_archived = FALSE WHERE id = $restore_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: archive.php?message=restored");
        exit();
    } else {
        echo "Error restoring record: " . $conn->error;
    }
}

// Handle permanent delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Delete associated files if they exist
    $file_sql = "SELECT image, video_path FROM herbs WHERE id = $delete_id";
    $file_result = $conn->query($file_sql);
    if ($file_row = $file_result->fetch_assoc()) {
        if (!empty($file_row['image']) && file_exists($file_row['image'])) {
            unlink($file_row['image']);
        }
        if (!empty($file_row['video_path']) && file_exists($file_row['video_path'])) {
            unlink($file_row['video_path']);
        }
    }
    
    $sql = "DELETE FROM herbs WHERE id = $delete_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: archive.php?message=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Get archived herbs
$sql = "SELECT * FROM herbs WHERE is_archived = TRUE ORDER BY date_added DESC";
$result = $conn->query($sql);

// Get counts
$total_archived = $result ? $result->num_rows : 0;
$total_active = $conn->query("SELECT COUNT(*) as count FROM herbs WHERE is_archived = 0")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🗄️ Herbal Wonders Archive</title>
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
            background: rgba(251, 191, 36, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .leaf-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(251, 191, 36, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
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
        
        .btn-restore {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            transition: all 0.3s ease;
        }
        
        .btn-restore:hover {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.4);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
        }
        
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #f59e0b, #fbbf24);
            border-radius: 5px;
        }
        
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.7);
        }

        .alert-success {
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-archive text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent">
                            HERBAL WONDERS
                        </h1>
                        <p class="text-xs text-gray-400">Archive Management</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-2">
                    <a href="admin.php" class="px-4 py-2 text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
                        <i class="fas fa-tasks mr-2"></i>Manage
                    </a>
                    <a href="archive.php" class="px-4 py-2 glass-strong text-amber-300 rounded-lg font-medium hover:bg-amber-600 hover:text-white transition-all">
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
            
            <!-- Success Messages -->
            <?php if (isset($_GET['message']) && $_GET['message'] == 'restored'): ?>
                <div class="alert-success mb-6 glass-card border-l-4 border-green-500 rounded-xl p-4 flex items-center">
                    <i class="fas fa-check-circle text-green-400 text-2xl mr-3"></i>
                    <span class="text-green-300">Herb successfully restored to active list!</span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['message']) && $_GET['message'] == 'deleted'): ?>
                <div class="alert-success mb-6 glass-card border-l-4 border-red-500 rounded-xl p-4 flex items-center">
                    <i class="fas fa-trash-alt text-red-400 text-2xl mr-3"></i>
                    <span class="text-red-300">Herb permanently deleted from database!</span>
                </div>
            <?php endif; ?>

            <!-- Header Section -->
            <div class="mb-8 animate-fade-in leaf-pattern rounded-2xl p-8 glass-card">
                <h2 class="text-3xl font-bold text-center mb-4 bg-gradient-to-r from-amber-300 to-amber-500 bg-clip-text text-transparent">
                    <i class="fas fa-archive mr-3"></i>Archived Herbs
                </h2>
                <p class="text-center text-gray-300 max-w-2xl mx-auto">
                    Manage your archived herbs. You can restore them back to the active list or permanently delete them from the database.
                </p>
            </div>

            <!-- Warning Banner -->
            <div class="mb-8 glass-card border-l-4 border-amber-500 rounded-xl p-6 flex items-start">
                <i class="fas fa-exclamation-triangle text-amber-400 text-3xl mr-4 mt-1"></i>
                <div>
                    <h3 class="text-amber-300 font-bold text-lg mb-2">Important Information</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        This page shows archived herbs. You can <strong class="text-green-400">restore</strong> them back to the active list or <strong class="text-red-400">permanently delete</strong> them. 
                        <span class="text-red-400 font-semibold">Warning: Permanent deletion cannot be undone!</span>
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6 hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Archived Herbs</p>
                            <p class="text-3xl font-bold text-amber-400">
                                <?php echo $total_archived; ?>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-archive text-amber-400 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="glass-card rounded-xl p-6 hover-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Active Herbs</p>
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
                            <p class="text-gray-400 text-sm">Total Database</p>
                            <p class="text-3xl font-bold text-blue-400">
                                <?php echo $total_archived + $total_active; ?>
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-database text-blue-400 text-2xl"></i>
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
                                <div class="md:w-2/5 h-64 md:h-auto bg-gradient-to-br from-amber-900/50 to-amber-700/50 flex items-center justify-center relative overflow-hidden">
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="<?= htmlspecialchars($row['image']) ?>" 
                                             alt="<?= htmlspecialchars($row['name']) ?>" 
                                             class="w-full h-full object-cover opacity-70">
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-black/20"></div>
                                        <i class="fas fa-seedling text-6xl text-amber-400/30 relative z-10"></i>
                                    <?php endif; ?>
                                    <div class="absolute top-3 right-3 bg-amber-500/80 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        <i class="fas fa-archive mr-1"></i>ARCHIVED
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-amber-600"></div>
                                </div>
                                
                                <!-- Content Section -->
                                <div class="md:w-3/5 p-6 flex flex-col">
                                    <div class="mb-4">
                                        <h3 class="text-2xl font-bold text-amber-300 mb-1">
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
                                                class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500 hover:text-white transition-all font-medium text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                        <a href="archive.php?restore_id=<?= $row['id'] ?>" 
                                           onclick="return confirm('Restore this herb back to active list?')"
                                           class="px-4 py-2 btn-restore text-white rounded-lg font-medium text-sm flex items-center justify-center">
                                            <i class="fas fa-undo mr-1"></i>Restore
                                        </a>
                                        <a href="archive.php?delete_id=<?= $row['id'] ?>" 
                                           onclick="return confirm('⚠️ WARNING: This will PERMANENTLY DELETE this herb!\n\nThis action cannot be undone!\n\nAre you absolutely sure?')"
                                           class="px-4 py-2 btn-delete text-white rounded-lg font-medium text-sm flex items-center justify-center">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="col-span-2 glass-card rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-archive text-4xl text-amber-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-300 mb-2">No archived herbs</h3>
                        <p class="text-gray-400 mb-6">Your archive is empty. Archived herbs will appear here.</p>
                        <a href="admin.php" class="inline-block px-6 py-3 btn-restore text-white rounded-lg font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Admin Panel
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Back Button -->
            <?php if ($result && $result->num_rows > 0): ?>
            <div class="mt-8 text-center">
                <a href="admin.php" class="inline-block px-8 py-4 glass-card text-gray-300 rounded-xl font-medium hover:glass-strong transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Admin Panel
                </a>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="glass-card rounded-3xl max-w-5xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
            <!-- Modal Header -->
            <div class="sticky top-0 glass-strong rounded-t-3xl p-6 flex items-center justify-between border-b border-amber-500/30 z-10">
                <div>
                    <h2 class="text-3xl font-bold text-amber-300" id="modalName">Herb Name</h2>
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
                        <h3 class="text-lg font-bold text-amber-300 mb-3 flex items-center">
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
                        <h3 class="text-lg font-bold text-amber-300 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>Description
                        </h3>
                        <p id="modalDescription" class="text-gray-300 text-sm leading-relaxed">
                            No description available
                        </p>
                    </div>
                    
                    <div class="glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-amber-300 mb-3 flex items-center">
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

                    <div id="creditsSection" class="hidden glass-strong rounded-xl p-5">
                        <h3 class="text-lg font-bold text-amber-300 mb-3 flex items-center">
                            <i class="fas fa-user mr-2"></i>Video Credits
                        </h3>
                        <p id="modalCredits" class="text-gray-300 text-sm leading-relaxed"></p>
                    </div>
                    
                    <button id="modalYoutubeBtn" class="hidden w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all">
                        <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                    </button>
                </div>
            </div>
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

                // Handle video credits
                const creditsSection = document.getElementById('creditsSection');
                const creditsSpan = document.getElementById('modalCredits');
                if (data.video_credits && data.video_credits.trim() !== "") {
                    creditsSpan.textContent = data.video_credits;
                    creditsSection.classList.remove('hidden');
                } else {
                    creditsSection.classList.add('hidden');
                }
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        document.querySelector('.modal-backdrop')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('viewModal')?.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
<?php
$conn->close();
?>