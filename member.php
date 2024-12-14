<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to upgrade to a membership.");
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ecom");
if (!$conn) {
    die("Error: Could not connect to the database. " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Handle membership upgrade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membership_expiry = date('Y-m-d', strtotime('+1 year')); // Membership valid for 1 year

    $sql = "UPDATE users SET role = 'member', membership_expiry = '$membership_expiry' WHERE id = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Congratulations! You are now a member.";
    } else {
        echo "Error upgrading membership: " . mysqli_error($conn);
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Membership</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upgrade to Membership</h1>
        <p>Become a member to enjoy exclusive discounts and benefits for a whole year!</p>
        <form method="POST" action="">
            <button type="submit">Upgrade to Member</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>