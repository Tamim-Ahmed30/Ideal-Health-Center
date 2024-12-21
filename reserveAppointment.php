<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
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

// Get doctor_id from query string
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

// Fetch doctor details
$doctor_sql = "SELECT fullname AS doctor_name, specialization AS department FROM doctors WHERE id = $doctor_id LIMIT 1";
$doctor_result = $conn->query($doctor_sql);

if ($doctor_result->num_rows == 0) {
    die("Doctor not found.");
}

$doctor = $doctor_result->fetch_assoc();

// Fetch user details
$user_sql = "SELECT fullname, email, phone FROM users WHERE username = '" . $conn->real_escape_string($_SESSION['username']) . "' LIMIT 1";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows == 0) {
    die("User not found.");
}

$user = $user_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $age = intval($_POST['age']);
    $visiting_date = $conn->real_escape_string($_POST['visiting_date']);
    $reason = $conn->real_escape_string($_POST['reason']);

    $appointment_sql = "INSERT INTO appointments (doctor_id, user_username, age, visiting_date, reason) VALUES ($doctor_id, '" . $conn->real_escape_string($_SESSION['username']) . "', $age, '$visiting_date', '$reason')";

    if ($conn->query($appointment_sql) === TRUE) {
        echo "<script>alert('Appointment successfully reserved!'); window.location.href='home.php';</script>";
    } else {
        echo "<script>alert('Failed to reserve appointment.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Appointment - Ideal Health Center</title>
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

    <!-- Reservation Form -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold text-blue-600 mb-8 text-center">Reserve Appointment</h1>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <input type="text" id="department" name="department" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($doctor['department']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="doctor_name" class="block text-sm font-medium text-gray-700">Doctor's Name</label>
                    <input type="text" id="doctor_name" name="doctor_name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($user['fullname']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" id="age" name="age" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="visiting_date" class="block text-sm font-medium text-gray-700">Visiting Date</label>
                    <input type="date" id="visiting_date" name="visiting_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Visit</label>
                    <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the reason for your visit..." required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Reserve Appointment</button>
            </form>
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
