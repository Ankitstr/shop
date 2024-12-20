<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: Please log in to proceed to checkout.");
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ecom");
if (!$conn) {
    die("Error: Could not connect to the database. " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Fetch user role
$sql = "SELECT role FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$role = $user['role'];

// Fetch cart items
$cart_sql = "SELECT c.*, p.name, p.price, p.price * c.quantity AS total 
             FROM cart c
             LEFT JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = '$user_id'";
$cart_result = mysqli_query($conn, $cart_sql);

$grand_total = 0;
while ($row = mysqli_fetch_assoc($cart_result)) {
    $grand_total += $row['total'];
}

// Apply a discount if the user is a member
$discount = 0;
if ($role === 'member') {
    $discount = $grand_total * 0.10; // 10% discount
}
$final_total = $grand_total - $discount;

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_sql = "INSERT INTO orders (user_id, total_amount, status) 
              VALUES ('$user_id', '$final_total', 'pending')";
    if (mysqli_query($conn, $order_sql)) {
        $order_id = mysqli_insert_id($conn);

        // Insert order items
        mysqli_data_seek($cart_result, 0); // Reset cart result pointer
        while ($row = mysqli_fetch_assoc($cart_result)) {
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '{$row['product_id']}', '{$row['quantity']}', '{$row['price']}')";
            mysqli_query($conn, $item_sql);
        }

        // Clear the cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

        echo "Order placed successfully! Your final total is ₹" . number_format($final_total, 2);
    } else {
        echo "Error placing order: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .checkout-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        button {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .summary {
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>
        <p>Total: ₹<?php echo number_format($grand_total, 2); ?></p>
        <?php if ($discount > 0): ?>
            <p>Member Discount: ₹<?php echo number_format($discount, 2); ?></p>
        <?php endif; ?>
        <p class="summary">Final Total: ₹<?php echo number_format($final_total, 2); ?></p>
        <form method="POST" action="">
           <a href="checkout.php"> <button type="submit">Place Order</button></a>
        </form>
    </div>
</body>
</html>
