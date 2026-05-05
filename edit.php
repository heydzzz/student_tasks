<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($stmt = $conn->prepare("SELECT * FROM tasks WHERE id=?")) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row) {
                echo "<p>Task not found.</p>";
                exit;
            }
        } else {
            echo "<p>Error: " . htmlspecialchars($conn->error) . "</p>";
            exit;
        }
    } else {
        echo "<p>No task ID provided.</p>";
        exit;
    }
    ?>

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

</body>

</html>