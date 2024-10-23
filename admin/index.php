<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Manage Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Manage Users</a>
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

    <div class="container mt-5">
        <h2 class="mb-4">Manage Events</h2>

        
        <div class="card mb-4">
            <div class="card-header">Add New Event</div>
            <div class="card-body">
                <form id="addEventForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="eventName" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="eventName" name="name" placeholder="Enter event name" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="eventDate" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="eventLocation" name="location" placeholder="Enter location" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventCategory" class="form-label">Category</label>
                        <select class="form-select" id="eventCategory" name="category" required>
                            <option selected disabled value="">Choose...</option>
                            <option value="Music">Music</option>
                            <option value="Sports">Sports</option>
                            <option value="Art">Art</option>
                            <option value="Technology">Technology</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="eventPhotos" class="form-label">Upload Photos</label>
                        <input type="file" class="form-control" id="eventPhotos" name="photos[]" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary" id="addEventButton">Add Event</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">All Events</div>
            <div class="card-body">
                <table class="table" id="eventTableBody">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="photosModal" tabindex="-1" aria-labelledby="photosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photosModalLabel">Event Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="photosContainer" class="row">
                        <!-- Photos will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/admin.js"></script> <!-- Make sure the path is correct -->
</body>
</html>
