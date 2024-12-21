<?php
session_start();

// Check if the user is logged in as a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php"); // Redirect to login page if not logged in or not a doctor
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

// Fetch the doctor's ID from the doctors table using username from session
$username = $_SESSION['username'];
$doctor_id_stmt = $conn->prepare("SELECT id FROM doctors WHERE username = ?");
$doctor_id_stmt->bind_param("s", $username);
$doctor_id_stmt->execute();
$doctor_id_result = $doctor_id_stmt->get_result();
$doctor_data = $doctor_id_result->fetch_assoc();
$doctor_id_stmt->close();

if (!$doctor_data) {
    die("Doctor ID not found. Please ensure the username is correct.");
}

$doctor_id = $doctor_data['id'];

$stmt = $conn->prepare(
    "SELECT a.id, a.age, a.visiting_date, a.reason, a.status, a.created_at, a.user_username, a.doctor_id
     FROM appointments a
     JOIN users u ON a.user_username = u.username
     JOIN doctors d ON a.doctor_id = d.id
     WHERE a.doctor_id = ?
     ORDER BY a.visiting_date ASC"
);
$stmt->bind_param("s", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['action'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'cancelled', 'visited'])) {
        $update_stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
        $update_stmt->bind_param("sis", $action, $appointment_id, $doctor_id);
        $update_stmt->execute();
        $update_stmt->close();

        header("Location: patient_appointments.php"); // Refresh the page to reflect changes
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Appointments - Ideal Health Center</title>
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
            <h1 class="text-4xl font-bold text-blue-600 mb-8 text-center">Patient Appointments</h1>
            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-lg rounded-lg">
                        <thead>
                            <tr class="bg-blue-600 text-white">
                                <th class="py-3 px-6 text-left">Patient Name</th>
                                <th class="py-3 px-6 text-left">Age</th>
                                <th class="py-3 px-6 text-left">Visiting Date</th>
                                <th class="py-3 px-6 text-left">Reason</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $result->fetch_assoc()): ?>
                                <tr class="border-b">
                                    <td class="py-3 px-6">
                                        <?php echo htmlspecialchars($appointment['user_username']); ?>
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
                                    <td class="py-3 px-6">
                                        <?php if ($appointment['status'] === 'waiting'): ?>
                                            <form action="" method="POST" class="inline-block">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                <button type="submit" name="action" value="approved" class="bg-green-600 text-white py-1 px-3 rounded hover:bg-green-700">Approve</button>
                                            </form>
                                            <form action="" method="POST" class="inline-block ml-2">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                <button type="submit" name="action" value="cancelled" class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700">Cancel</button>
                                            </form>
                                        <?php elseif ($appointment['status'] === 'approved'): ?>
                                            <form action="" method="POST" class="inline-block">
                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                <button type="submit" name="action" value="visited" class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Mark as Visited</button>
                                            </form>
                                        <?php endif; ?>
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
$stmt->close();
$conn->close();
?>
