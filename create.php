<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $scientificname = trim($_POST['scientificname']);
    $characteristics = trim($_POST['characteristics']);
    $uses = trim($_POST['uses']);
    $description = trim($_POST['description']);
    $precautions = trim($_POST['precautions']);
    $youtube_link = trim($_POST['youtube_link']);
    $video_credits = trim($_POST['video_credits']); // NEW LINE

    // Check for duplicates before escaping
    $stmt_check = $conn->prepare("SELECT * FROM herbs WHERE name = ? AND scientificname = ? LIMIT 1");
    $stmt_check->bind_param("ss", $name, $scientificname);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

   if ($result_check->num_rows > 0) {
    $_SESSION['error'] = "Herb with this name and scientific name already exists.";
    header("Location: admin.php");
    exit();
}

    // Escape data before insert
    $name = $conn->real_escape_string($name);
    $scientificname = $conn->real_escape_string($scientificname);
    $characteristics = $conn->real_escape_string($characteristics);
    $uses = $conn->real_escape_string($uses);
    $description = $conn->real_escape_string($description);
    $precautions = $conn->real_escape_string($precautions);
    $youtube_link = $conn->real_escape_string($youtube_link);
    $video_credits = $conn->real_escape_string($video_credits); // NEW LINE

    // ===== Handle image upload =====
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgDir = "uploads/images/";
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);

        $imgName = time() . "_" . basename($_FILES['image']['name']);
        $imgPath = $imgDir . $imgName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imgPath)) {
            $image = $conn->real_escape_string($imgPath);
        }
    }

    // ===== Handle video upload =====
    $video_path = "";
    if (isset($_FILES['video_path']) && $_FILES['video_path']['error'] == 0) {
        $vidDir = "uploads/videos/";
        if (!is_dir($vidDir)) mkdir($vidDir, 0777, true);

        $vidName = time() . "_" . basename($_FILES['video_path']['name']);
        $vidPath = $vidDir . $vidName;

        if (move_uploaded_file($_FILES['video_path']['tmp_name'], $vidPath)) {
            $video_path = $conn->real_escape_string($vidPath);
        }
    }

    // ===== Insert into database =====
    $sql = "INSERT INTO herbs (name, scientificname, characteristics, uses, description, precautions, image, video_path, youtube_link, video_credits)
            VALUES ('$name','$scientificname','$characteristics','$uses','$description','$precautions','$image','$video_path','$youtube_link','$video_credits')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>