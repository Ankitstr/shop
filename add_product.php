<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];

    $conn = mysqli_connect("localhost", "root", "", "ecom");
    $sql = "INSERT INTO products (name, price, description, brand_id, category_id) 
            VALUES ('$name', '$price', '$description', '$brand_id', '$category_id')";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    echo "<script>alert('Product added successfully!');</script>";
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 10px;
            text-align: center;
            padding: 10px;
            display: none;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Product</h2>
    <form method="POST" id="productForm">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        
        <select name="brand_id" required>
            <option value="">Select Brand</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ecom");
            $result = mysqli_query($conn, "SELECT * FROM brands");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            mysqli_close($conn);
            ?>
        </select>

        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "ecom");
            $result = mysqli_query($conn, "SELECT * FROM categories");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            mysqli_close($conn);
            ?>
        </select>

        <button type="submit">Add Product</button>
    </form>
</div>

<script>
    // Add dynamic message display
    document.getElementById('productForm').addEventListener('submit', function(e) {
        let brand = document.querySelector("select[name='brand_id']").value;
        let category = document.querySelector("select[name='category_id']").value;

        if (!brand || !category) {
            e.preventDefault();
            alert('Please select both a brand and a category.');
        }
    });
    
</script>

</body>
</html>
