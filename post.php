<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_post'])) {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $media_url = '';

    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $media_url = 'uploads/' . basename($_FILES['media']['name']);
        move_uploaded_file($_FILES['media']['tmp_name'], $media_url);
    }

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, media_url) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $content, $media_url]);

    $_SESSION['message'] = "Post submitted successfully!";
    header('Location: dashboard.php');
    exit();
}
?>
