<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    
    // Archive herb by setting is_archived to 1 (TRUE)
    $sql = "UPDATE herbs SET is_archived = 1 WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: admin.php?message=archived");
        exit();
    } else {
        echo "Error archiving record: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "Invalid request - No ID provided.";
}

$conn->close();
?>