<?php
session_start();
// Check if the user is logged in and has a valid role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['doctor', 'user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in or invalid role
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Ideal Health Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <div class="text-2xl font-bold">Ideal Health Center</div>
        <ul class="flex space-x-6">
            <li><a href="home.php" class="hover:underline">Home</a></li>
            <li><a href="about.php" class="hover:underline">About</a></li>
            <li><a href="department.php" class="hover:underline">Department</a></li>
            <li><a href="contact.php" class="hover:underline">Contact</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li class="ml-4">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></li>
            <?php endif; ?>
            <li><a href="?logout=true" class="hover:underline text-red-400">Logout</a></li>
        </ul>
    </nav>

    <!-- Contact Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Map Section -->
            <div>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0868564723833!2d-122.40641708468122!3d37.78583417975762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085818e5b6df1b9%3A0x15aa85cbfaa0c76a!2sIdeal%20Health%20Center!5e0!3m2!1sen!2sus!4v1695123456789!5m2!1sen!2sus" 
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Contact Details Section -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-blue-600 mb-6">Contact Us</h1>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    If you have any questions or need assistance, feel free to reach out to us. Our team is here to help you with any inquiries you may have.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    <strong>Email:</strong> support@idealhealthcenter.com
                </p>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    <strong>Phone:</strong> +1 (800) 123-4567
                </p>
                <p class="text-lg text-gray-600 leading-relaxed">
                    <strong>Address:</strong> 123 Health St., Wellness City, USA
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6 text-center">
        <p>&copy; <?php echo date('Y'); ?> Ideal Health Center. All rights reserved.</p>
    </footer>
</body>
</html>