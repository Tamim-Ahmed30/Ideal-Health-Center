<?php
session_start();
// Check if the user is logged in and has a valid role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['doctor', 'user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in or invalid role
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_site";
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get department from query string
$department = isset($_GET['department']) ? $conn->real_escape_string($_GET['department']) : '';

// Fetch doctors based on department
$sql = "SELECT id, fullname, username, email, phone, specialization, created_at FROM doctors WHERE specialization = '$department'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors in <?php echo htmlspecialchars($department); ?> - Ideal Health Center</title>
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

    <!-- Doctors Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-blue-600 mb-8 text-center">Doctors in <?php echo htmlspecialchars($department); ?></h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($doctor = $result->fetch_assoc()): ?>
                        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                            <h2 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($doctor['fullname']); ?></h2>
                            <p class="text-gray-600">Username: <?php echo htmlspecialchars($doctor['username']); ?></p>
                            <p class="text-gray-600">Email: <?php echo htmlspecialchars($doctor['email']); ?></p>
                            <p class="text-gray-600">Phone: <?php echo htmlspecialchars($doctor['phone']); ?></p>
                            <p class="text-gray-600">Specialization: <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                            <a href="reserveAppointment.php?doctor_id=<?php echo $doctor['id']; ?>" class="mt-4 inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Reserve Appointment</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-600 text-center col-span-3">No doctors found in this department.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6 text-center">
        <p>&copy; <?php echo date('Y'); ?> Ideal Health Center. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
