<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="styles.css">
    <script src="javascript.js" defer></script>
</head>
<body>
    <header>
        <h1>Contact Us</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Get in Touch</h2>
        <p>If you have any questions, feel free to reach out!</p>
        
        <!-- Alert area untuk menampilkan pesan -->
        <div id="alert-message" style="display: none; padding: 10px; margin: 10px 0; border-radius: 5px;"></div>
        
        <form id="contact-form" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required minlength="2">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" required minlength="10" rows="5"></textarea>
            
            <button type="submit" id="submit-btn">Send</button>
        </form>
        
        <h3>Contact Information</h3>
        <p>Phone: <a href="tel:+6285348850351">+62-853-4885-0351</a></p>
        <p>Instagram: <a href="https://instagram.com/gabryela_rombeallo" target="_blank">@gabryela_rombeallo</a></p>
        <p>Email: <a href="mailto:gabryelarombeallo@gmail.com">gabryelarombeallo@gmail.com</a></p>
    </main>
    <footer>
        <p>&copy; GABRYELA'S PERSONAL HOMEPAGE</p>
    </footer>

    <script>
    // JavaScript untuk handle form submission dengan AJAX
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submit-btn');
        const alertDiv = document.getElementById('alert-message');
        
        // Disable button saat loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        
        // Ambil data form
        const formData = new FormData(form);
        
        // Kirim data dengan fetch API
        fetch('process_contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Tampilkan pesan
            alertDiv.style.display = 'block';
            alertDiv.textContent = data.message;
            
            if (data.success) {
                alertDiv.style.backgroundColor = '#d4edda';
                alertDiv.style.color = '#155724';
                alertDiv.style.border = '1px solid #c3e6cb';
                form.reset(); // Reset form jika berhasil
            } else {
                alertDiv.style.backgroundColor = '#f8d7da';
                alertDiv.style.color = '#721c24';
                alertDiv.style.border = '1px solid #f5c6cb';
            }
            
            // Hide alert after 5 seconds
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.style.display = 'block';
            alertDiv.style.backgroundColor = '#f8d7da';
            alertDiv.style.color = '#721c24';
            alertDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        })
        .finally(() => {
            // Enable button kembali
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send';
        });
    });
    </script>
</body>
</html>