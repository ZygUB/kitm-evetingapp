<?php
session_start();
require 'api/db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$pageTitle = "User Dashboard - Add Event";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EventApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar" aria-controls="userNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   
    <div class="container">
        <h2 class="mb-4">Submit New Event</h2>

        
        <div id="alertContainer"></div>

       
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
                    <button type="submit" class="btn btn-primary" id="addEventButton">Submit Event</button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <strong>Your Submitted Events</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Event Name</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="userEventsTableBody">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/dashboard.js"></script>
</body>
</html>
