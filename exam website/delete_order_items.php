<?php
// Connection details
$host = "localhost"; 
$user = "ishimwe"; 
$pass = "222005870"; 
$database = "online_debt_managment_course_platform";

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if product_id is set
if(isset($_REQUEST['product_id'])) {
    $product_id = $_REQUEST['product_id'];
    
    // Prepare and execute the DELETE statement
    $stmt = $connection->prepare("DELETE FROM order_items WHERE id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product.";
    }
    
    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Product Record</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this product?");
        }
    </script>
</head>
<body>
    <form method="post" onsubmit="return confirmDelete();">
        <input type="hidden" name="product_id" value="<?php echo isset($product_id) ? $product_id : ''; ?>">
        <input type="submit" value="Delete">
    </form>
</body>
</html>
