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
    </style>
</head>
<body>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addEventForm').addEventListener('submit', function(e) {
                e.preventDefault(); 
                const formData = new FormData(this);
                formData.append('status', 'pending');

                
                fetch('api/add_event.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Event submitted successfully and is awaiting approval!', 'success');
                        
                        document.getElementById('addEventForm').reset();
                        
                        fetchUserEvents();
                    } else {
                        showAlert('Error: ' + data.error, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error adding event:', error);
                    showAlert('An unexpected error occurred. Please try again later.', 'danger');
                });
            });

            
            function showAlert(message, type) {
                const alertContainer = document.getElementById('alertContainer');
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                alertContainer.appendChild(wrapper);

                
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
                    alert.close();
                }, 5000);
            }

            
            function fetchUserEvents() {
                fetch(`api/fetch_user_events.php?user_id=<?= urlencode($_SESSION['user_id']) ?>`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderUserEvents(data.events);
                        } else {
                            showAlert('Error fetching your events: ' + data.error, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user events:', error);
                        showAlert('An unexpected error occurred while fetching your events.', 'danger');
                    });
            }

            
            function renderUserEvents(events) {
                const userEventsTableBody = document.getElementById('userEventsTableBody');
                userEventsTableBody.innerHTML = ''; 

                if (!events || events.length === 0) {
                    userEventsTableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">You have not submitted any events yet.</td>
                        </tr>
                    `;
                    return;
                }

                events.forEach(event => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${event.id}</td>
                        <td>${escapeHtml(event.name)}</td>
                        <td>${escapeHtml(event.date)}</td>
                        <td>${escapeHtml(event.location)}</td>
                        <td>${escapeHtml(event.category)}</td>
                        <td>
                            ${event.status === 'pending' ? 
                                '<span class="badge bg-warning text-dark">Pending</span>' : 
                                (event.status === 'approved' ? 
                                    '<span class="badge bg-success">Approved</span>' : 
                                    '<span class="badge bg-danger">Rejected</span>')
                            }
                        </td>
                    `;
                    userEventsTableBody.appendChild(row);
                });
            }

            
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            
            fetchUserEvents();
        });
    </script>
</body>
</html>
