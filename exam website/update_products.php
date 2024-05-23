<?php
// Connection details
$host = "localhost";
$user = "evariste";
$pass = "222005870";
$database = "food";

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if product_id is set
if (isset($_REQUEST['product_id'])) {
    $product_id = $_REQUEST['product_id'];

    // Use prepared statement
    $stmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $description = htmlspecialchars($row['description'], ENT_QUOTES);
        $price = htmlspecialchars($row['price'], ENT_QUOTES);
        $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES);
        $category = htmlspecialchars($row['category'], ENT_QUOTES);
    } else {
        echo "Product not found.";
        exit();
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body><center>
    <!-- Update products form -->
    <h2><u>Update Form of Products</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label for="name">Product Name:</label>
        <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
        <br><br>

        <label for="description">Description:</label>
        <input type="text" name="description" value="<?php echo isset($description) ? $description : ''; ?>" required>
        <br><br>

        <label for="price">Price:</label>
        <input type="text" name="price" value="<?php echo isset($price) ? $price : ''; ?>" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="text" name="quantity" value="<?php echo isset($quantity) ? $quantity : ''; ?>" required>
        <br><br>

        <label for="category">Category:</label>
        <input type="text" name="category" value="<?php echo isset($category) ? $category : ''; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>

</body>
</html>

<?php
// Handle form submission
if (isset($_POST['up'])) {
    // Retrieve updated values from the form
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES);
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES);
    $quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES);

    // Use prepared statement for update
    $stmt = $connection->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, category = ? WHERE id = ?");
    $stmt->bind_param("ssdisi", $name, $description, $price, $quantity, $category, $product_id);

    if ($stmt->execute()) {
        // Redirect to products.php on successful update
        header('Location: products.php');
        exit(); // Ensure that no other content is sent after the header redirection
    } else {
        // Handle error (e.g., display an error message)
        echo "Failed to update. Please try again.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$connection->close();
?>
