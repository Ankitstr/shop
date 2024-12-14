<?php
//session_start();
// Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

// $user_id = $_SESSION['user_id']; // Get logged-in user ID

// // Database connection
// $conn = mysqli_connect("localhost", "root", "", "ecom");

// // Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }


session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to add products to the cart.");
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ecom");
if (!$conn) {
    die("Error: Could not connect to the database. " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID
$message = "";

// Handle adding a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $message = "Product is already in your cart.";
    } else {
        // Add product to cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
        if (mysqli_query($conn, $sql)) {
            $message = "Product added to cart successfully.";
        } else {
            $message = "Error adding product to cart: " . mysqli_error($conn);
        }
    }
}
// Query to fetch cart details
$sql = "SELECT p.name, c.quantity, p.price, p.price * c.quantity AS total
        FROM cart c
        LEFT JOIN products p ON c.product_id = p.id
        WHERE c.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

// HTML and CSS for cart display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .cart-table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .cart-table th {
            background-color: #4CAF50;
            color: white;
        }
        .cart-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .cart-table tr:hover {
            background-color: #ddd;
        }
        .cart-total {
            margin: 20px auto;
            text-align: right;
            width: 80%;
        }
        .cart-total strong {
            font-size: 1.2em;
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
    <h1 style="text-align:center;">Your Shopping Cart</h1>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (per item)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_total = 0; // Initialize grand total
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $grand_total += $row['total'];
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['total']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="cart-total">
        <strong>Grand Total: RS.<?php echo number_format($grand_total, 2); ?></strong>
    

    </div>

    <a href="checkout.php"><button>Proceed to Checkout</button></a>
</body>
</html>
<?php
// Close database connection
mysqli_close($conn);
?>
