<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $scientificname = $_POST['scientificname'];
    $characteristics = $_POST['characteristics'];
    $uses = $_POST['uses'];
    $description = $_POST['description'];
    $precautions = $_POST['precautions'];
    $youtube_link = $_POST['youtube_link'];
    $video_credits = $_POST['video_credits']; // NEW LINE

    // Get current image/video from DB
    $result = $conn->query("SELECT image, video_path FROM herbs WHERE id=$id");
    $row = $result->fetch_assoc();
    $image = $row['image'];
    $video_path = $row['video_path'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        }
    }

    // Handle video upload
    if (!empty($_FILES['video_path']['name'])) {
        $targetDir = "uploads/videos/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($_FILES['video_path']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;
        if (move_uploaded_file($_FILES['video_path']['tmp_name'], $targetFile)) {
            $video_path = $targetFile;
        }
    }

    // Update DB
    $sql = "UPDATE herbs 
            SET name=?, scientificname=?, characteristics=?, uses=?, 
                description=?, precautions=?, image=?, video_path=?, youtube_link=?, video_credits=?
            WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $name, $scientificname, $characteristics, $uses, $description, $precautions, $image, $video_path, $youtube_link, $video_credits, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>