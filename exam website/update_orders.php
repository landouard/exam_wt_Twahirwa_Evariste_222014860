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

// Check if order_id is set
if (isset($_REQUEST['order_id'])) {
    $order_id = $_REQUEST['order_id'];

    // Use prepared statement
    $stmt = $connection->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = htmlspecialchars($row['user_id'], ENT_QUOTES);
        $total_amount = htmlspecialchars($row['total_amount'], ENT_QUOTES);
        $order_date = htmlspecialchars($row['order_date'], ENT_QUOTES);
    } else {
        echo "Order not found.";
        exit();
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body><center>
    <!-- Update orders form -->
    <h2><u>Update Form of Orders</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" value="<?php echo isset($user_id) ? $user_id : ''; ?>" required>
        <br><br>

        <label for="total_amount">Total Amount:</label>
        <input type="text" name="total_amount" value="<?php echo isset($total_amount) ? $total_amount : ''; ?>" required>
        <br><br>

        <label for="order_date">Order Date:</label>
        <input type="date" name="order_date" value="<?php echo isset($order_date) ? $order_date : ''; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>

</body>
</html>

<?php
// Handle form submission
if (isset($_POST['up'])) {
    // Retrieve updated values from the form
    $user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES);
    $total_amount = htmlspecialchars($_POST['total_amount'], ENT_QUOTES);
    $order_date = htmlspecialchars($_POST['order_date'], ENT_QUOTES);

    // Use prepared statement for update
    $stmt = $connection->prepare("UPDATE orders SET user_id = ?, total_amount = ?, order_date = ? WHERE id = ?");
    $stmt->bind_param("idsi", $user_id, $total_amount, $order_date, $order_id);

    if ($stmt->execute()) {
        // Redirect to orders.php on successful update
        header('Location: orders.php');
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
