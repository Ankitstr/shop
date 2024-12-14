<?php
$successMessage = "";
$errorMessage = "";


//include 'include/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = md5($_POST['password']); // For security, use password_hash in a real-world application
    $email = $_POST['email'];
    
    $conn = mysqli_connect("localhost", "root", "", "ecom");
    $checkQuery = "SELECT * FROM users WHERE name = '$name' OR email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $errorMessage = "Username or email already exists.";
    } else {

    $sql = " INSERT INTO users(name , email,password) VALUES ('$name', '$email','$password')";
    if (mysqli_query($conn, $sql)) {
        // Redirect to a new page after successful registration
        header("Location: login.php");
        exit();
    } else {
        $errorMessage = "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
}
?>
<!-- <form method="POST">
    <input type="text" name="name" placeholder="name" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Register</button>
</form> -->


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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
        <h2>Register</h2>

        <?php if ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
            <?php elseif ($errorMessage): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
     
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>