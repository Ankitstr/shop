<?php
$successMessage="";
session_start();

define('SESSION_TIMEOUT', 1800); // 30 minutes

// Check if the session is active
if (isset($_SESSION['last_activity'])) {
    // Calculate the session's lifetime
    $session_lifetime = time() - $_SESSION['last_activity'];
    if ($session_lifetime > SESSION_TIMEOUT) {
        // Destroy the session and redirect to login
        session_unset();
        session_destroy();
        header("Location: login.php?message=Session expired. Please log in again.");
        exit;
    }
}
        
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = md5($_POST['password']); // For security, use password_hash in a real-world application
    
    $conn = mysqli_connect("localhost", "root", "", "ecom");
    $sql = "SELECT * FROM users WHERE name='$name' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
       // $_SESSION['username'] = $name;
        // Redirect to another page (for example, dashboard.php)
        $row = mysqli_fetch_assoc($result);
         $_SESSION['user_id'] = $row['id'];
        header("Location: index.php");
        exit(); // Ensure no further code is executed after redirect
    } else {
        $successMessage = "Invalid credentials!";
    }
    mysqli_close($conn);
}
 
// $conn = mysqli_connect("localhost", "root", "", "ecom");
// // Check if form is submitted
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $name = mysqli_real_escape_string($conn, $_POST['name']);
//     $password = mysqli_real_escape_string($conn, $_POST['password']);

//     // Check credentials
//     $sql = "SELECT id FROM users WHERE name = '$name' AND password = '$password'";
//     $result = mysqli_query($conn, $sql);

//     if (mysqli_num_rows($result) == 1) {
//         $row = mysqli_fetch_assoc($result);
//         $_SESSION['user_id'] = $row['id']; // Set user ID in session
//         header("Location: cart.php"); // Redirect after login
//     } else {
//         echo "Invalid username or password.";
//     }
// }
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            color: white;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>

        <?php if ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
           
     
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>