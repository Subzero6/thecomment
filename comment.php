<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : NULL;

    // Check if the post_id exists
    $stmt = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if ($post) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, parent_comment_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $content, $parent_comment_id]);
        $_SESSION['message'] = "Comment submitted!";
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Invalid post ID.";
    }
}
?>
