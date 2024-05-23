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
if(isset($_REQUEST['product_id'])) {
    $product_id = $_REQUEST['product_id'];
    
    // Prepare and execute the DELETE statement
    $stmt = $connection->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $product_id);
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
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($stmt->execute()) {
        echo "Product record deleted successfully.";
    } else {
        echo "Error deleting product record: " . $stmt->error;
    }
    }
?>
</body>
</html>
<?php

    $stmt->close();
} else {
    echo "product_id is not set.";
}

$connection->close();
?>
