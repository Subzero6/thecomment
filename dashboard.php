<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Display success message if set
if (isset($_SESSION['message'])) {
    echo "<div class='message'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']);
}

// Fetch posts
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // Display posts
    foreach ($posts as $post) {
        echo "<div class='post'>";
        echo "<p>{$post['content']}</p>";
        if ($post['media_url']) {
            echo "<img src='{$post['media_url']}' alt='Media'>";
        }
        echo "<form action='like.php' method='POST'>
                <input type='hidden' name='post_id' value='{$post['id']}'>
                <button type='submit' name='like_post'>Like</button>
              </form>";
        echo "<form action='share.php' method='POST'>
                <input type='hidden' name='post_id' value='{$post['id']}'>
                <button type='submit' name='share_post'>Share</button>
              </form>";

        // Fetch and display comments
        $comments = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND parent_comment_id IS NULL ORDER BY created_at ASC");
        $comments->execute([$post['id']]);
        foreach ($comments as $comment) {
            echo "<div class='comment'>";
            echo "<p>{$comment['content']}</p>";
            echo "<form action='comment.php' method='POST'>
                    <input type='hidden' name='post_id' value='{$post['id']}'>
                    <input type='hidden' name='parent_comment_id' value='{$comment['id']}'>
                    <textarea name='content'></textarea>
                    <button type='submit' name='submit_comment'>Reply</button>
                  </form>";

            // Fetch and display replies
            $replies = $pdo->prepare("SELECT * FROM comments WHERE parent_comment_id = ? ORDER BY created_at ASC");
            $replies->execute([$comment['id']]);
            foreach ($replies as $reply) {
                echo "<div class='reply'>";
                echo "<p>{$reply['content']}</p>";
                echo "</div>";
            }

            echo "</div>";
        }

        echo "<form action='comment.php' method='POST'>
                <input type='hidden' name='post_id' value='{$post['id']}'>
                <textarea name='content' placeholder='Write a comment...' required></textarea>
                <button type='submit' name='submit_comment'>Comment</button>
              </form>";

        echo "</div>";
    }
    ?>
</body>
</html>
