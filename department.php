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
    <title>Departments - Ideal Health Center</title>
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

    <!-- Departments Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-blue-600 mb-8 text-center">Our Departments</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Cardiology">
                        <h2 class="text-xl font-bold mb-4">Cardiology</h2>
                        <p class="text-gray-600">Comprehensive care for heart-related issues.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Neurology">
                        <h2 class="text-xl font-bold mb-4">Neurology</h2>
                        <p class="text-gray-600">Expert care for brain and nervous system disorders.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Orthopedics">
                        <h2 class="text-xl font-bold mb-4">Orthopedics</h2>
                        <p class="text-gray-600">Specialized treatment for bones and joints.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Pediatrics">
                        <h2 class="text-xl font-bold mb-4">Pediatrics</h2>
                        <p class="text-gray-600">Comprehensive healthcare for children.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Gynecology">
                        <h2 class="text-xl font-bold mb-4">Gynecology</h2>
                        <p class="text-gray-600">Women’s health and maternity care services.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Dermatology">
                        <h2 class="text-xl font-bold mb-4">Dermatology</h2>
                        <p class="text-gray-600">Skin care and treatment of skin conditions.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=ENT">
                        <h2 class="text-xl font-bold mb-4">ENT</h2>
                        <p class="text-gray-600">Specialized care for ear, nose, and throat issues.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Oncology">
                        <h2 class="text-xl font-bold mb-4">Oncology</h2>
                        <p class="text-gray-600">Advanced cancer treatment and care.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Radiology">
                        <h2 class="text-xl font-bold mb-4">Radiology</h2>
                        <p class="text-gray-600">State-of-the-art imaging and diagnostics.</p>
                    </a>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                    <a href="doctorList.php?department=Psychiatry">
                        <h2 class="text-xl font-bold mb-4">Psychiatry</h2>
                        <p class="text-gray-600">Mental health care and psychological services.</p>
                    </a>
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
