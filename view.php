<?php
include 'db.php';

if (!isset($_GET['id'])) { die("❌ No herb selected."); }
$id = intval($_GET['id']);
$sql = "SELECT * FROM herbs WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) { die("❌ Herb not found."); }
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($row['name']) ?> - Herb Details</title>
<style>
body { font-family: Arial, sans-serif; background: #f1f8f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
.modal { background: white; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); width: 80%; max-width: 900px; padding: 20px; display: flex; flex-wrap: wrap; gap: 20px; }
.modal img { width: 250px; height: 350px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
.info { flex: 1; display: flex; flex-direction: column; gap: 10px; }
.info h2 { margin: 0; text-align: center; color: green; }
.info p { margin: 0; font-size: 15px; text-align: justify; }
video { width: 100%; max-height: 300px; border: 1px solid #ccc; border-radius: 6px; }
.actions { margin-top: 15px; text-align: center; }
.btn { padding: 8px 14px; border-radius: 6px; text-decoration: none; margin: 0 5px; font-size: 14px; }
.btn.back { background: #555; color: white; }
.btn.edit { background: #1976d2; color: white; }
.btn.archive { background: #ff9800; color: white; }
.btn:hover { opacity: 0.85; }
</style>
</head>
<body>
<div class="modal">
  <?php if (!empty($row['image'])): ?>
    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
  <?php else: ?>
    <img src="no-image.png" alt="No Image">
  <?php endif; ?>

  <div class="info">
    <h2><?= htmlspecialchars($row['name']) ?></h2>
    <p><strong>Scientific Name:</strong> <?= htmlspecialchars($row['scientificname']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
    <p><strong>Characteristics:</strong> <?= htmlspecialchars($row['characteristics']) ?></p>
    <p><strong>Uses:</strong> <?= htmlspecialchars($row['uses']) ?></p>
    <p><strong>Precautions:</strong> <?= htmlspecialchars($row['precautions']) ?></p>

    <?php if (!empty($row['youtube_link'])): ?>
      <p><strong>YouTube Link:</strong> 
        <a href="<?= htmlspecialchars($row['youtube_link']) ?>" target="_blank">Watch Video</a>
      </p>
    <?php endif; ?>

    <?php if (!empty($row['video_path'])): ?>
      <video controls>
        <source src="<?= htmlspecialchars($row['video_path']) ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php endif; ?>

    <?php if (!empty($row['video_credits'])): ?>
      <p><strong>Video Credits:</strong> <?= htmlspecialchars($row['video_credits']) ?></p>
    <?php endif; ?>

    <div class="actions">
      <a href="admin.php" class="btn back">⬅ Back</a>
      <a href="edit.php?id=<?= $row['id'] ?>" class="btn edit">Edit</a>
      <a href="archive_herb.php?id=<?= $row['id'] ?>" class="btn archive" onclick="return confirm('Archive this herb?')">Archive</a>
    </div>
  </div>
</div>
</body>
</html>