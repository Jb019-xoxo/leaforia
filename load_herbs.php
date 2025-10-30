<?php
include 'db.php';

$limit = 12; // number of herbs per batch
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

$offset = ($page - 1) * $limit;

if ($search !== "") {
    // FIXED: Added is_archived = 0 to exclude archived herbs from search
    $sql = "SELECT * FROM herbs 
            WHERE is_archived = 0 AND (
                name LIKE '%$search%' 
                OR description LIKE '%$search%' 
                OR scientificname LIKE '%$search%' 
                OR characteristics LIKE '%$search%' 
                OR uses LIKE '%$search%' 
                OR precautions LIKE '%$search%'
            )
            ORDER BY name ASC
            LIMIT $limit OFFSET $offset";
} else {
    // FIXED: Added is_archived = 0 to exclude archived herbs from default view
    $sql = "SELECT * FROM herbs WHERE is_archived = 0 ORDER BY name ASC LIMIT $limit OFFSET $offset";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while($row = $result->fetch_assoc()):
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
            
            <!-- Action Button -->
            <button onclick='openViewModal(<?= htmlspecialchars($data, ENT_QUOTES) ?>)' 
                    class="w-full px-6 py-3 btn-herbal text-white rounded-lg font-medium transition-all hover:shadow-xl"
                    onmouseover="this.innerHTML='<i class=\'fas fa-eye mr-2\'></i>Click me !'" 
                    onmouseout="this.innerHTML='<i class=\'fas fa-eye mr-2\'></i>View Details'">
                <i class="fas fa-eye mr-2"></i>View Details
            </button>
        </div>
    </div>
</div>
<?php
    endwhile;
endif;
?>