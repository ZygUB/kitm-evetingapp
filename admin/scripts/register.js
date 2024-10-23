document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const formData = new FormData(this);

    fetch('../admin/api/register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '../admin/login.html';
        } else {
            document.querySelector('.card').innerHTML += `
                <div class="alert alert-danger">${data.error}</div>
            `;
        }
    })
    .catch(error => {
        console.error('Registration error:', error);
    });
});
