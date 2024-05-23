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

// Check if order_id is set
if(isset($_REQUEST['order_id'])) {
    $order_id = $_REQUEST['order_id'];
    
    // Prepare and execute the DELETE statement
    $stmt = $connection->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    
    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Order deleted successfully.";
    } else {
        echo "Error deleting order.";
    }
    
    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Order Record</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this order?");
        }
    </script>
</head>
<body>
    <form method="post" onsubmit="return confirmDelete();">
        <input type="hidden" name="order_id" value="<?php echo isset($order_id) ? $order_id : ''; ?>">
        <input type="submit" value="Delete">
    </form>
</body>
</html>
