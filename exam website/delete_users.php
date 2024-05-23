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

// Check if user_id is set
if(isset($_REQUEST['user_id'])) {
    $user_id = $_REQUEST['user_id'];
    
    // Prepare and execute the DELETE statement
    $stmt = $connection->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete User Record</title>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this user?");
            }
        </script>
    </head>
    <body>
        <form method="post" onsubmit="return confirmDelete();">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($stmt->execute()) {
        echo "User record deleted successfully.";
    } else {
        echo "Error deleting user record: " . $stmt->error;
    }
    }
?>
</body>
</html>
<?php

    $stmt->close();
} else {
    echo "user_id is not set.";
}

$connection->close();
?>
