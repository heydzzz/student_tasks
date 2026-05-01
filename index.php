<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Task Management</title>

    <!-- CSS LINK -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <h2>📚 Student Task Management</h2>

        <a href="add.php">+ Add New Task</a>

        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <link rel="stylesheet" href="style.css">
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= $row['due_date'] ?></td>

                        <td>
                            <?php if ($row['status'] == "Completed") { ?>
                                <span style="color:#22c55e;">✔ Completed</span>
                            <?php } else { ?>
                                <span style="color:#facc15;">⏳ Pending</span>
                            <?php } ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5'>No tasks found</td></tr>";
            }
            ?>

        </table>

    </div>

</body>

</html>