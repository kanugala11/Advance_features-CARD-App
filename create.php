<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    if ($conn->query($sql)) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Create Post</h2>
<form method="POST">
    Title: <input type="text" name="title" required><br>
    Content:<br>
    <textarea name="content" rows="5" cols="40" required></textarea><br>
    <input type="submit" value="Create">
</form>
<a href="index.php">Back</a>