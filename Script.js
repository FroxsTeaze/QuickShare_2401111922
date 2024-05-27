// script.js
// script.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    const credentials = [
        { username: 'Muzakky', password: '230303' },
        { username: 'Rizal', password: '111111' },
        { username: 'Andiaz', password: '222222' },
        { username: 'Widia', password: '333333' },
        { username: 'Karma', password: '444444' },
        { username: 'Dullah', password: '555555' },
        { username: 'Fauzi', password: '666666' },
        { username: 'admin', password: 'admin123' }  // Admin credentials
        // Tambahkan pasangan username dan password lain jika diperlukan
    ];

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const usernameValue = usernameInput.value.trim();
        const passwordValue = passwordInput.value.trim();

        const isValidCredentials = credentials.some(cred => 
            cred.username === usernameValue && cred.password === passwordValue
        );

        if (isValidCredentials) {
            if (usernameValue === 'admin') {
                window.location.href = 'Admin page/Adminpage.html'; // Ganti dengan halaman yang diinginkan untuk admin
            } else {
                window.location.href = 'User page/Userpage.php'; // Halaman untuk pengguna non-admin
            }
        } else {
            alert('Username atau Password salah.');
        }
    });
});


