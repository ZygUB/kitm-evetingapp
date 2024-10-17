<?php
session_start();
require 'api/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$message = '';

if (isset($_GET['ban_user_id'])) {
    $ban_user_id = intval($_GET['ban_user_id']);
    
    if ($stmt = $mysqli->prepare("UPDATE users SET status = 'banned' WHERE id = ?")) {
        $stmt->bind_param("i", $ban_user_id);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            User has been banned successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error banning user: ' . htmlspecialchars($stmt->error) . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Database error: ' . htmlspecialchars($mysqli->error) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
    }
}

$users = [];
if ($result = $mysqli->query("SELECT id, username, email, status FROM users WHERE role != 'admin'")) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $result->free();
} else {
    $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error fetching users: ' . htmlspecialchars($mysqli->error) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .container {
            margin-top: 30px;
        }
        .table-actions {
            min-width: 100px;
        }
    </style>
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Manage Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html">Go to Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">User Management</h2>
        
        <?php echo $message; ?>

        <div class="card">
            <div class="card-header">
                <strong>Registered Users</strong>
            </div>
            <div class="card-body">
                <?php if (count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="table-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <?php if ($user['status'] == 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Banned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($user['status'] == 'active'): ?>
                                                <a href="users.php?ban_user_id=<?= urlencode($user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to ban this user?');">Ban</a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled>Ban</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">No users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
