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

// Initialize session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $conn->real_escape_string($_POST['username_or_email']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Role: user or doctor

    if ($role === 'user') {
        // Fetch user by username or email
        $sql = "SELECT id, username, email, password FROM users WHERE username = '$username_or_email' OR email = '$username_or_email' LIMIT 1";
    } elseif ($role === 'doctor') {
        // Fetch doctor by username or email
        $sql = "SELECT id, username, email, password FROM doctors WHERE username = '$username_or_email' OR email = '$username_or_email' LIMIT 1";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;

            if ($role === 'user') {
                header("Location: home.php"); // Redirect to user homepage
            } elseif ($role === 'doctor') {
                header("Location: home.php"); // Redirect to doctor dashboard
            }
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with the given username or email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medical Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 text-gray-800 font-sans">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-extrabold text-center mb-4 text-blue-700">Ideal Health Center</h1>
            <h2 class="text-2xl font-bold text-center mb-6 text-blue-600">Login</h2>
            <?php if (!empty($error_message)) : ?>
                <div class="mb-4 text-red-600 text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="username_or_email" class="block text-sm font-medium text-gray-700">Username or Email</label>
                    <input type="text" id="username_or_email" name="username_or_email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your username or email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password" required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Login as</label>
                    <select id="role" name="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="user">User</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Login</button>
            </form>
            <p class="text-sm text-center mt-4">Don't have an account? <a href="registration.php" class="text-blue-600 hover:underline">Register here</a></p>
        </div>
    </div>
</body>
</html>
