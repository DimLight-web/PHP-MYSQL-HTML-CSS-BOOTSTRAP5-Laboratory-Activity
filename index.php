<?php
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if ($name != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $name, $email);

        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit();
        }
        $stmt->close();
    } else {
        $error = "Please enter a valid name and email address.";
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$msg = $_GET["msg"] ?? "";
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="text-center mb-4">User Management System</h2>

    <?php
    $messages = [
        "added" => "User added successfully!",
        "updated" => "User updated successfully!",
        "deleted" => "User deleted successfully!"
    ];

    if (isset($messages[$msg])) {
        echo '<div class="alert alert-success">'
            . htmlspecialchars($messages[$msg]) . '</div>';
    }

    if (isset($error)) {
        echo '<div class="alert alert-danger">'
            . htmlspecialchars($error) . '</div>';
    }
    ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Add New User</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>








    <div class="card shadow-sm">
        <div class="card-header">Registered Users</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Full Name</th><th>Email Address</th>
                            <th>Date Created</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($users && $users->num_rows > 0): ?>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["ID"] ?></td>
                            <td><?= htmlspecialchars($row["Name"]) ?></td>
                            <td><?= htmlspecialchars($row["Email"]) ?></td>
                            <td><?= htmlspecialchars($row["created_at"]) ?></td>
                            <td>
                                <a class="btn btn-warning btn-sm"
                                   href="edit.php?id=<?= $row["ID"] ?>">Edit</a>
                                <a class="btn btn-danger btn-sm"
                                   href="delete.php?id=<?= $row["ID"] ?>"
                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                   Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No users found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
