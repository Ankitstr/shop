<?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "ecom");

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// SQL query for filtering products
$sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.name LIKE '%$search%'";

if ($brand) {
    $sql .= " AND b.name = '$brand'";
}
if ($category) {
    $sql .= " AND c.name = '$category'";
}

$result = mysqli_query($conn, $sql);

// Fetch brands and categories for filtering
$brands_result = mysqli_query($conn, "SELECT * FROM brands");
$categories_result = mysqli_query($conn, "SELECT * FROM categories");
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">*Shop</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="member.php">Member</a>
        </nav>
    </header>

    <!-- Search and Filter Section -->
    <section class="search-filter">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search Products" value="<?php echo $search; ?>">
            <select name="brand">
                <option value="">Select Brand</option>
                <?php while ($row = mysqli_fetch_assoc($brands_result)): ?>
                    <option value="<?php echo $row['name']; ?>" <?php echo ($row['name'] == $brand) ? 'selected' : ''; ?>>
                        <?php echo $row['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select name="category">
                <option value="">Select Category</option>
                <?php while ($row = mysqli_fetch_assoc($categories_result)): ?>
                    <option value="<?php echo $row['name']; ?>" <?php echo ($row['name'] == $category) ? 'selected' : ''; ?>>
                        <?php echo $row['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Search</button>
        </form>
    </section>

    <!-- Product List Section -->
    <section class="product-list">
        <h1>Products</h1>
        <a href="add_product.php">Add_product</a>

        <div class="products">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product">

                    <h2><?php echo $row['name']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <p><strong>Price: $<?php echo $row['price']; ?></strong></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; simple shope</p>
    </footer>
</body>
</html>
<?php mysqli_close($conn); ?>
