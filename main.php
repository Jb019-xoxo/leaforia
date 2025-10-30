<?php
include 'db.php';
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🌿 Herbal Wonders</title>
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

    .spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #2e7d32;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: auto;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .modal-open {
        overflow: hidden;
    }
</style>
</head>
<body class="text-gray-100 font-sans">
    <!-- Header with Glass Effect -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-card">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3 animate-float cursor-pointer" onclick="window.location.href='index.php'">
                    <div class="w-12 h-12 bg-gradient-to-br from-herbal-400 to-herbal-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-leaf text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent">
                            HERBAL WONDERS
                        </h1>
                        <p class="text-xs text-gray-400">Natural Medicine Database</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-2">
                    <a href="index.php" class="px-4 py-2  text-herbal-300 rounded-lg font-medium hover:bg-herbal-600 hover:text-white transition-all">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="main.php" class="px-4 py-2 glass-strong text-gray-300 rounded-lg font-medium hover:glass-strong transition-all">
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
                <h2 class="text-3xl font-bold text-center mb-6 bg-gradient-to-r from-herbal-300 to-herbal-500 bg-clip-text text-transparent cursor-pointer" onclick="window.location.href='main.php'">
                    <i class="fas fa-search mr-3"></i>Discover Herbal Remedies
                </h2>
                <form id="searchForm" class="flex gap-3 max-w-3xl mx-auto">
                    <input 
                        type="text" 
                        name="search" 
                        id="searchInput"
                        value="<?php echo htmlspecialchars($search); ?>"
                        placeholder="Search by name, disease, or description..." 
                        class="flex-1 px-6 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none search-glow focus:border-herbal-400 transition-all"
                    >
                    <button type="submit" class="px-8 py-4 btn-herbal text-white rounded-xl font-semibold flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </button>
                </form>
            </div>

            <!-- Herbs Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="cardsContainer">
                <!-- Cards will be loaded here via JavaScript -->
            </div>

            <!-- Loading Spinner -->
            <div id="loading" class="hidden text-center my-8">
                <div class="spinner mx-auto"></div>
                <p class="text-gray-400 mt-4">Loading herbs...</p>
            </div>
        </div>
    </main>

    <!-- View Modal -->
    <div id="herbModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="glass-card rounded-3xl max-w-5xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
            <!-- Modal Header -->
            <div class="sticky top-0 glass-strong rounded-t-3xl p-6 flex items-center justify-between border-b border-herbal-500/30 z-10">
                <div>
                    <h2 class="text-3xl font-bold text-herbal-300" id="modalName">Herb Name</h2>
                    <p class="text-sm text-gray-400 italic" id="modalScientific">Scientific name</p>
                </div>
                <button onclick="closeModal('herbModal')" class="w-10 h-10 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded-full transition-all">
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

    <script>
        let page = 1;
        let loading = false;
        let lastPage = false;

        function fetchHerbs(reset = false) {
            if (loading || lastPage) return;
            loading = true;
            document.getElementById('loading').classList.remove('hidden');

            const search = document.getElementById('searchInput').value;
            fetch(`load_herbs.php?page=${page}&search=${encodeURIComponent(search)}`)
                .then(res => res.text())
                .then(data => {
                    if (reset) {
                        document.getElementById('cardsContainer').innerHTML = '';
                    }
                    if (data.trim() === '') {
                        lastPage = true;
                        if (page === 1) {
                            // Show empty state
                            document.getElementById('cardsContainer').innerHTML = `
                                <div class="col-span-2 glass-card rounded-2xl p-12 text-center">
                                    <div class="w-20 h-20 bg-herbal-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-seedling text-4xl text-herbal-400"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-300 mb-2">No herbs found</h3>
                                    <p class="text-gray-400">Try adjusting your search terms</p>
                                </div>
                            `;
                        }
                    } else {
                        document.getElementById('cardsContainer').insertAdjacentHTML('beforeend', data);
                        page++;
                    }
                    loading = false;
                    document.getElementById('loading').classList.add('hidden');
                });
        }

        // Infinite scroll
        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                fetchHerbs();
            }
        });

        // Search
        document.getElementById('searchForm').addEventListener('submit', (e) => {
            e.preventDefault();
            page = 1;
            lastPage = false;
            fetchHerbs(true);
        });

        // MODAL FUNCTIONS
        function openViewModal(data) {
            const modal = document.getElementById('herbModal');
            
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
            
            document.body.classList.add('modal-open');
            modal.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal-backdrop').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal('herbModal');
                }
            });
        });

        // Escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('herbModal');
            }
        });

        // Initial load
        fetchHerbs();
    </script>
</body>
</html>