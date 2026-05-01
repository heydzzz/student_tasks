<?php include 'db.php'; ?>

<?php
$id = $_POST['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$date = $_POST['due_date'];
$status = $_POST['status'];

$conn->query("UPDATE tasks SET
    title='$title',
    description='$desc',
    due_date='$date',
    status='$status'
    WHERE id=$id");

header("Location: index.php");
?>

<head>
    <title>Update Task</title>
    <link rel="stylesheet" href="style.css">
</head>