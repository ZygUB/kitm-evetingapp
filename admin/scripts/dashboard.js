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
                userEventsTableBody.innerHTML = ''; // Clear existing rows

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