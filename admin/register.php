<?php
require 'api/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkUser = $mysqli->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($checkUser->num_rows > 0) {
        $message = '<div class="alert alert-danger">Username or Email already exists.</div>';
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($mysqli->query($sql)) {
            $message = '<div class="alert alert-success">Registration successful! Redirecting to login...</div>';
            header("refresh:3;url=login.php");
        } else {
            $message = '<div class="alert alert-danger">Error: ' . $mysqli->error . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 70px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
        }
        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">EvetingApp</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container form-container">
        <div class="card">
            <h3 class="mb-4 text-center">Register</h3>
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
