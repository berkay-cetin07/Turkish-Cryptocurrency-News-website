<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

require_once __DIR__ . '/../../src/db.php'; // Include the database connection file

// If the user is already logged in, redirect to the appropriate page
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /admin.php"); // Redirect admin users to the admin panel
    } else {
        header("Location: /?page=home"); // Redirect normal users to the home page
    }
    exit; // Stop further execution
}

// Variable to store error messages
$error = "";

// Handle the login process
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    // Collect user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";

    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare($query);

        // Bind parameters to the query
        $stmt->bindParam(':username', $username, PDO::PARAM_STR); // Bind the username as a string
        $stmt->bindParam(':password', $password, PDO::PARAM_STR); // Bind the password as a string

        // Execute the prepared statement
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user details
            $_SESSION['username'] = $user['username']; // Set the username in the session
            $_SESSION['role'] = $user['role']; // Set the user's role in the session

            // Redirect the user based on their role
            if ($user['role'] === 'admin') {
                header("Location: /admin.php"); // Admin users are redirected to the admin panel
            } else {
                header("Location: /?page=home"); // Normal users are redirected to the home page
            }
            exit; // Stop further execution
        } else {
            // Display an error message for invalid credentials
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        // Catch database errors and display a generic error message
        $error = "An error occurred while processing your request. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        /* General styling for the page */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            position: relative;
        }

        
        .login-container .profile-icon {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #1b4332;
            font-size: 40px;
            margin: -60px auto 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .login-container h2 {
            color: #1b4332;
            margin-bottom: 30px;
            font-size: 24px;
        }

        
        .form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #555;
            font-size: 16px;
        }

        .form input:focus {
            outline: none;
            border-color: #457fca;
        }

        
        .form .btn {
            width: 100%;
            padding: 12px;
            background-color: #457fca;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .form .btn:hover {
            background-color: #5691c8;
        }

        .error {
            color: #e63946;
            margin-bottom: 15px;
        }

        
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="profile-icon">
            &#128100; 
        </div>
        <h2>Giriş Yapın</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" class="form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        <div class="footer">
        </div>
    </div>
</body>
</html>
