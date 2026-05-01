<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$conn->query("DELETE FROM tasks WHERE id=$id");

header("Location: index.php");
?>

<head>
    <title>Delete Task</title>
    <link rel="stylesheet" href="style.css">
</head>