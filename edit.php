<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tasks WHERE id=$id");
$row = $result->fetch_assoc();
?>

<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <input type="text" name="title" value="<?= $row['title'] ?>"><br>
    <textarea name="description"><?= $row['description'] ?></textarea><br>
    <input type="date" name="due_date" value="<?= $row['due_date'] ?>"><br>

    <select name="status">
        <option <?= $row['status'] == "Pending" ? "selected" : "" ?>>Pending</option>
        <option <?= $row['status'] == "Completed" ? "selected" : "" ?>>Completed</option>
    </select><br>

    <button>Update</button>
</form>