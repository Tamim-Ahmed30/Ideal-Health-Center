<?php
// Start by creating a database connection (update credentials accordingly)
$host = "localhost";
$username = "root";
$password = "";
$dbname = "medical_site";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate a unique username based on the fullname
function generateUsername($fullname, $conn) {
    $baseUsername = strtolower(str_replace(' ', '_', $fullname));
    $username = $baseUsername;
    $counter = 1;

    while (true) {
        $result = $conn->query("SELECT id FROM doctors WHERE username = '$username'");
        if ($result->num_rows == 0) {
            break;
        }
        $username = $baseUsername . '_' . $counter;
        $counter++;
    }

    return $username;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $specialization = $conn->real_escape_string($_POST['specialization']);

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $username = generateUsername($fullname, $conn); // Generate a unique username

        // Insert doctor into the database
        $sql = "INSERT INTO doctors (fullname, email, phone, password, username, specialization) VALUES ('$fullname', '$email', '$phone', '$hashed_password', '$username', '$specialization')";


        if ($conn->query($sql) === TRUE) {
            header("Location: login.php"); // Redirect to login page
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Passwords do not match!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration - Medical Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-extrabold text-center mb-4 text-blue-700">Ideal Health Center</h1>
            <h2 class="text-2xl font-bold text-center mb-6 text-blue-600">Doctor Registration</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your full name" required oninput="generateUsername()">
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your phone number" required>
                </div>
                <div class="mb-4">
                    <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                    <select id="specialization" name="specialization" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Select your specialization</option>
                        <option value="Cardiology">Cardiology</option>
                        <option value="Neurology">Neurology</option>
                        <option value="Orthopedics">Orthopedics</option>
                        <option value="Pediatrics">Pediatrics</option>
                        <option value="Gynecology">Gynecology</option>
                        <option value="Dermatology">Dermatology</option>
                        <option value="ENT">ENT</option>
                        <option value="Oncology">Oncology</option>
                        <option value="Radiology">Radiology</option>
                        <option value="Psychiatry">Psychiatry</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Create a password" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Confirm your password" required oninput="checkPasswordMatch()">
                    <p id="password_match_message" class="text-sm mt-2"></p>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Register</button>
            </form>
        </div>
    </div>

<script>
    function generateUsername() {
        const fullname = document.getElementById('fullname').value;
        const usernameField = document.getElementById('username');
        const username = fullname.toLowerCase().replace(/\s+/g, '_');
        usernameField.value = username;
    }
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const message = document.getElementById('password_match_message');

        if (confirmPassword.length === 0) {
            message.textContent = ""; // Clear message if confirm password field is empty
            message.className = "";
        } else if (password === confirmPassword) {
            message.textContent = "Passwords match!";
            message.className = "text-green-600";
        } else {
            message.textContent = "Passwords do not match.";
            message.className = "text-red-600";
        }
    }
</script>
</body>
</html>
