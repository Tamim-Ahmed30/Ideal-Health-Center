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
    <title>About Us - Ideal Health Center</title>
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

    <!-- About Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-blue-600 mb-6">About Us</h1>
            <p class="text-lg text-gray-600 leading-relaxed mb-6">
                Welcome to Ideal Health Center, where our mission is to provide high-quality healthcare services with compassion and dedication. Our experienced team of doctors, nurses, and healthcare professionals is committed to ensuring the well-being of every patient. 
            </p>
            <p class="text-lg text-gray-600 leading-relaxed">
                With state-of-the-art facilities and a patient-centered approach, we offer a range of medical services designed to meet the diverse needs of our community. From routine check-ups to specialized treatments, we are here to support you every step of the way.
            </p>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-gray-100 px-4 md:px-16">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-blue-600 mb-8 text-center">Meet Our Team</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-xl font-bold mb-2">Dr. John Doe</h3>
                    <p class="text-gray-600">Cardiologist</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-xl font-bold mb-2">Dr. Jane Smith</h3>
                    <p class="text-gray-600">Neurologist</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-xl font-bold mb-2">Dr. Alice Brown</h3>
                    <p class="text-gray-600">Pediatrician</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <h3 class="text-xl font-bold mb-2">Dr. Bob Johnson</h3>
                    <p class="text-gray-600">Orthopedic Surgeon</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6 text-center">
        <p>&copy; <?php echo date('Y'); ?> Ideal Health Center. All rights reserved.</p>
    </footer>
</body>
</html>
