<?php include 'db.php'; ?>

<?php
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['due_date'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO tasks (title, description, due_date, status)
                  VALUES ('$title', '$desc', '$date', '$status')");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>

    <!-- ✅ CONNECT YOUR CSS HERE -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h2>Add New Task</h2>

    <form method="POST">
        <input type="text" name="title" placeholder="Task Title" required>

        <textarea name="description" placeholder="Description"></textarea>

        <input type="date" name="due_date">

        <select name="status">
            <option>Pending</option>
            <option>Completed</option>
        </select>

        <button name="submit">Add Task</button>
    </form>

    <br>
    <a href="index.php">← Back</a>

</div>

</body>
</html>