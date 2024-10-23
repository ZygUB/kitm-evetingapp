document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('api/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {
            // Redirect based on user role
            if (data.role === 'admin') {
                window.location.href = 'index.php'; 
            } else {
                window.location.href = 'user_dashboard.php'; 
            }
        } else {
            document.getElementById('messageContainer').innerHTML = `
                <div class="alert alert-danger">${data.error}</div>
            `;
        }
    })
    .catch(error => {
        console.error('Login error:', error);
    });
});
