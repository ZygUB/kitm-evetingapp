// Utility function to escape HTML to prevent XSS
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Function to render the status of the event
function renderStatus(status) {
    switch (status) {
        case 'pending':
            return '<span class="badge bg-warning text-dark">Pending</span>';
        case 'approved':
            return '<span class="badge bg-success">Approved</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Rejected</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}

// Fetch all events on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchEvents(); // Initial fetch of events

    // Event form submission
    document.getElementById('addEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('status', 'pending'); // Set default status

        fetch('api/add_event.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Event added successfully!', 'success');
                this.reset(); // Reset the form
                fetchEvents(); // Refresh the event list
            } else {
                showMessage('Error: ' + data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error adding event:', error);
            showMessage('An unexpected error occurred. Please try again.', 'danger');
        });
    });
});

// Fetch events function
function fetchEvents() {
    fetch('api/fetch_events.php')
        .then(response => response.json())
        .then(data => {
            renderEvents(data); // Render events to the table
        })
        .catch(error => console.error('Error fetching events:', error));
}

// Function to render events in the table
function renderEvents(events) {
    const eventTableBody = document.getElementById('eventTableBody');
    eventTableBody.innerHTML = ''; // Clear current events

    events.forEach(event => {
        if (event && event.id) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(event.id)}</td>
                <td>${escapeHtml(event.title)}</td>
                <td>${escapeHtml(event.start)}</td>
                <td>${escapeHtml(event.extendedProps.location)}</td>
                <td>${escapeHtml(event.extendedProps.category)}</td>
                <td>${renderStatus(event.extendedProps.status)}</td>
                <td>${renderActions(event.id)}</td>
            `;
            eventTableBody.appendChild(row);
        } else {
            console.warn('Event is undefined or missing id:', event);
        }
    });
}

// Function to render action buttons for each event
function renderActions(eventId) {
    return `
        <button class="btn btn-success btn-sm me-1" onclick="approveEvent(${eventId})">Approve</button>
        <button class="btn btn-danger btn-sm me-1" onclick="deleteEvent(${eventId})">Delete</button>
        <button class="btn btn-info btn-sm" onclick="viewPhotos(${eventId})">View Photos</button>
    `;
}

// Function to view photos for a specific event
function viewPhotos(eventId) {
    console.log(`Viewing photos for event ID: ${eventId}`);
    fetch(`api/fetch_photos.php?event_id=${eventId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(photos => {
        const photosContainer = document.getElementById('photosContainer');
        photosContainer.innerHTML = ''; // Clear existing photos

        if (Array.isArray(photos) && photos.length > 0) {
            photos.forEach(photo => {
                const col = document.createElement('div');
                col.classList.add('col-md-4', 'mb-3');
                col.innerHTML = `<img src="${escapeHtml(photo.photo_path)}" class="img-fluid img-thumbnail" alt="Event Photo">`;
                photosContainer.appendChild(col);
            });
        } else {
            photosContainer.innerHTML = '<p>No photos available for this event.</p>'; // No photos case
        }

        // Show the modal for photos
        const photosModal = new bootstrap.Modal(document.getElementById('photosModal'));
        photosModal.show();
    })
    .catch(error => console.error('Error fetching photos:', error)); // Handle fetch errors
}

// Approve Event function
function approveEvent(eventId) {
    console.log(`Approving event with ID: ${eventId}`);
    fetch('api/update_event.php', {
        method: 'POST',
        body: JSON.stringify({ id: eventId, status: 'approved' }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Event approved successfully!', 'success');
            fetchEvents(); // Refresh event list
        } else {
            showMessage('Error: ' + data.error, 'danger');
        }
    })
    .catch(error => console.error('Error approving event:', error));
}

// Delete Event function
function deleteEvent(eventId) {
    console.log(`Deleting event with ID: ${eventId}`);
    if (!confirm('Are you sure you want to delete this event?')) return;

    fetch('api/delete_event.php', {
        method: 'POST',
        body: JSON.stringify({ id: eventId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Event deleted successfully!', 'success');
            fetchEvents(); // Refresh event list
        } else {
            showMessage('Error: ' + data.error, 'danger');
        }
    })
    .catch(error => console.error('Error deleting event:', error));
}

// Function to display messages
function showMessage(message, type) {
    const messageContainer = document.createElement('div');
    messageContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    document.querySelector('.container').prepend(messageContainer);
    
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(messageContainer.querySelector('.alert'));
        alert.close();
    }, 5000);
}
