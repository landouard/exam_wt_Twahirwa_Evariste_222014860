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

// Check if order_item_id is set
if (isset($_REQUEST['order_item_id'])) {
    $order_item_id = $_REQUEST['order_item_id'];

    // Use prepared statement
    $stmt = $connection->prepare("SELECT * FROM order_items WHERE id = ?");
    $stmt->bind_param("i", $order_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = htmlspecialchars($row['order_id'], ENT_QUOTES);
        $product_id = htmlspecialchars($row['product_id'], ENT_QUOTES);
        $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES);
        $price = htmlspecialchars($row['price'], ENT_QUOTES);
    } else {
        echo "Order item not found.";
        exit();
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order Item</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body><center>
    <!-- Update order_items form -->
    <h2><u>Update Form of Order Items</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label for="order_id">Order ID:</label>
        <input type="text" name="order_id" value="<?php echo isset($order_id) ? $order_id : ''; ?>" required>
        <br><br>

        <label for="product_id">Product ID:</label>
        <input type="text" name="product_id" value="<?php echo isset($product_id) ? $product_id : ''; ?>" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="text" name="quantity" value="<?php echo isset($quantity) ? $quantity : ''; ?>" required>
        <br><br>

        <label for="price">Price:</label>
        <input type="text" name="price" value="<?php echo isset($price) ? $price : ''; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>

</body>
</html>

<?php
// Handle form submission
if (isset($_POST['up'])) {
    // Retrieve updated values from the form
    $order_id = htmlspecialchars($_POST['order_id'], ENT_QUOTES);
    $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES);
    $quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES);

    // Use prepared statement for update
    $stmt = $connection->prepare("UPDATE order_items SET order_id = ?, product_id = ?, quantity = ?, price = ? WHERE id = ?");
    $stmt->bind_param("iiidi", $order_id, $product_id, $quantity, $price, $order_item_id);

    if ($stmt->execute()) {
        // Redirect to order_items.php on successful update
        header('Location: order_items.php');
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
