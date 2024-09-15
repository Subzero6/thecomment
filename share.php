<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['share_post'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO shares (post_id, user_id) VALUES (?, ?)");
    $stmt->execute([$post_id, $user_id]);

    echo "Post shared!";
}
?>
