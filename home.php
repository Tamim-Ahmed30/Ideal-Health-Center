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
    <title>Home - Medical Website</title>
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

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-blue-400 text-white py-20 text-center">
        <h1 class="text-5xl font-bold">Welcome to Ideal Health Center</h1>
        <p class="mt-4 text-lg">Providing world-class healthcare services with dedication and care.</p>
        <a href="#" class="mt-6 inline-block bg-white text-blue-600 py-2 px-6 rounded-md font-semibold hover:bg-gray-200">Learn More</a>
    </header>

    <!-- Cards Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <a href="about.php">
                    <h2 class="text-xl font-bold mb-4">About Us</h2>
                    <p class="text-gray-600">Learn more about our mission, vision, and team of professionals.</p>
                </a>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <a href="department.php">
                    <h2 class="text-xl font-bold mb-4">Department</h2>
                    <p class="text-gray-600">Explore our specialized healthcare departments and services.</p>
                </a>
            </div>
            <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'user'): ?>
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <a href="doctor_appointment.php">
                    <h2 class="text-xl font-bold mb-4">Appointment a Doctor</h2>
                    <p class="text-gray-600">Schedule an appointment with our expert doctors.</p>
                </a>
            </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'doctor'): ?>
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <a href="patient_appointments.php">
                    <h2 class="text-xl font-bold mb-4">Appointments of the Patients</h2>
                    <p class="text-gray-600">Doctors can view and manage their patients' appointments.</p>
                </a>
            </div>
            <?php endif; ?>
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <a href="contact.php">
                    <h2 class="text-xl font-bold mb-4">Support</h2>
                    <p class="text-gray-600">Contact us for assistance and support at any time.</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6 text-center">
        <p>&copy; <?php echo date('Y'); ?> Ideal Health Center. All rights reserved.</p>
    </footer>
</body>
</html>
