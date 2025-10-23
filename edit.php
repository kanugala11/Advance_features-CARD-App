<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: index.php");
    }
}
?>
<h2>Edit Post</h2>
<form method="POST">
    Title: <input type="text" name="title" value="<?php echo $post['title']; ?>" required><br>
    Content:<br>
    <textarea name="content" rows="5" cols="40" required><?php echo $post['content']; ?></textarea><br>
    <input type="submit" value="Update">
</form>
<a href="index.php">Back</a>