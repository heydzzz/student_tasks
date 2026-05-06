<?php include 'data.php'; ?>

<?php
$id = $_POST['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$date = $_POST['due_date'];
$status = $_POST['status'];

$db->update($id, $title, $desc, $date, $status);

header("Location: index.php");
?>

<head>
    <title>Update Task</title>
    <link rel="stylesheet" href="style.css">
</head>