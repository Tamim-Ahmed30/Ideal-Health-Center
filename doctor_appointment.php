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

// Fetch appointments for the logged-in user
$user_username = $_SESSION['username'];
$sql = "SELECT d.fullname AS doctor_name, a.age, a.visiting_date, a.reason, a.created_at, a.status 
        FROM appointments a 
        JOIN doctors d ON a.doctor_id = d.id 
        WHERE a.user_username = '$user_username' 
        ORDER BY a.visiting_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments - Ideal Health Center</title>
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

    <!-- Appointments Section -->
    <section class="py-16 px-4 md:px-16">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-bold text-blue-600 mb-8 text-center">Your Appointments</h1>
            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-lg rounded-lg">
                        <thead>
                            <tr class="bg-blue-600 text-white">
                                <th class="py-3 px-6 text-left">Doctor Name</th>
                                <th class="py-3 px-6 text-left">Age</th>
                                <th class="py-3 px-6 text-left">Visiting Date</th>
                                <th class="py-3 px-6 text-left">Reason</th>
                                <th class="py-3 px-6 text-left">Booked At</th>
                                <th class="py-3 px-6 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $result->fetch_assoc()): ?>
                                <tr class="border-b">
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['doctor_name']); ?>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['age']); ?>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['visiting_date']); ?>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['reason']); ?>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['created_at']); ?>
                                    </td>
                                    <td class="py-3 px-6">
                                        <?php 
                                            switch ($appointment['status']) {
                                                case 'waiting':
                                                    echo "<span class='text-yellow-600'>Waiting for Approval</span>";
                                                    break;
                                                case 'approved':
                                                    echo "<span class='text-green-600'>Accepted</span>";
                                                    break;
                                                case 'cancelled':
                                                    echo "<span class='text-red-600'>Cancelled</span>";
                                                    break;
                                                case 'visited':
                                                    echo "<span class='text-blue-600'>Visited</span>";
                                                    break;
                                                default:
                                                    echo "<span class='text-gray-600'>Unknown</span>";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">No appointments found.</p>
            <?php endif; ?>
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
