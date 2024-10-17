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


$events = [];
$query = "SELECT id, name AS title, date AS start, location, category, status FROM events";
$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $result->free();
} else {
    $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error fetching events: ' . htmlspecialchars($mysqli->error) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
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
            min-width: 150px;
        }
    </style>
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
    <div class="container">
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

        <div class="card">
            <div class="card-header">
                <strong>All Events</strong>
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
                                <th class="table-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="eventTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="photosModal" tabindex="-1" aria-labelledby="photosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="photosContainer" class="row">
                    </div>
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
                        showAlert('Event added successfully and is awaiting approval!', 'success');
                        
                        document.getElementById('addEventForm').reset();
                        
                        fetchEvents();
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
                const alertContainer = document.createElement('div');
                alertContainer.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                document.querySelector('.container').prepend(alertContainer);

                
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertContainer.querySelector('.alert'));
                    alert.close();
                }, 5000);
            }

            
            function fetchEvents() {
                fetch('api/fetch_events.php')  
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); 
                        renderEvents(data);
                    })
                    .catch(error => console.error('Error fetching events:', error));
            }

            
            function renderEvents(events) {
                const eventTableBody = document.getElementById('eventTableBody');
                eventTableBody.innerHTML = '';  

                if (!Array.isArray(events) || events.length === 0) {
                    eventTableBody.innerHTML = '<tr><td colspan="7" class="text-center">No events available</td></tr>';
                    return;
                }

                events.forEach(event => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${escapeHtml(event.id)}</td>
                        <td>${escapeHtml(event.title)}</td> <!-- Changed from event.name to event.title -->
                        <td>${escapeHtml(event.start)}</td>  <!-- Changed from event.date to event.start -->
                        <td>${escapeHtml(event.extendedProps.location)}</td>
                        <td>${escapeHtml(event.extendedProps.category)}</td>
                        <td>
                            ${event.extendedProps.status === 'pending' ? '<span class="badge bg-warning text-dark">Pending</span>' : 
                              event.extendedProps.status === 'approved' ? '<span class="badge bg-success">Approved</span>' : 
                              '<span class="badge bg-danger">Rejected</span>'}
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm me-1" onclick="approveEvent(${event.id})">Approve</button>
                            <button class="btn btn-danger btn-sm me-1" onclick="deleteEvent(${event.id})">Delete</button>
                            <button class="btn btn-info btn-sm" onclick="viewPhotos(${event.id})">View Photos</button>
                        </td>
                    `;
                    eventTableBody.appendChild(row);
                });
            }

            
            window.approveEvent = function(eventId) {
                const formData = new FormData();
                formData.append('id', eventId);
                formData.append('status', 'approved');

                fetch('api/update_event.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Event approved successfully!', 'success');
                        fetchEvents(); 
                    } else {
                        showAlert('Error: ' + data.error, 'danger');
                    }
                })
                .catch(error => console.error('Error approving event:', error));
            }

            
            window.deleteEvent = function(eventId) {
                if(!confirm('Are you sure you want to delete this event?')) return;

                const formData = new FormData();
                formData.append('id', eventId);

                fetch('api/delete_event.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Event deleted successfully!', 'success');
                        fetchEvents();
                    } else {
                        showAlert('Error: ' + data.error, 'danger');
                    }
                })
                .catch(error => console.error('Error deleting event:', error));
            }

            
            window.viewPhotos = function(eventId) {
                fetch(`api/fetch_photos.php?event_id=${eventId}`)
                .then(response => response.json())
                .then(photos => {
                    const photosContainer = document.getElementById('photosContainer');
                    photosContainer.innerHTML = ''; 

                    if (Array.isArray(photos) && photos.length > 0) {
                        photos.forEach(photo => {
                            const col = document.createElement('div');
                            col.classList.add('col-md-4', 'mb-3');
                            col.innerHTML = `<img src="${escapeHtml(photo.photo_path)}" class="img-fluid img-thumbnail" alt="Event Photo">`;
                            photosContainer.appendChild(col);
                        });
                    } else {
                        photosContainer.innerHTML = '<p>No photos available for this event.</p>';
                    }

                    
                    const photosModal = new bootstrap.Modal(document.getElementById('photosModal'));
                    photosModal.show();
                })
                .catch(error => console.error('Error fetching photos:', error));
            }

            
            function escapeHtml(text) {
                if (typeof text !== 'string') return text;
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            
            fetchEvents();
        });
    </script>
</body>
</html>
