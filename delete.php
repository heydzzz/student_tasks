<?php include 'data.php'; ?>

<?php
$id = $_GET['id'];
$db->delete($id);

header("Location: index.php");
?>

<head>
    <title>Delete Task</title>
    <link rel="stylesheet" href="style.css">
</head>