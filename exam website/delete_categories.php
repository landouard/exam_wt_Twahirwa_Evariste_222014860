<?php
// Connection details
$host = "localhost"; 
$user = "ishimwe"; 
$pass = "222005870"; 
$database = "food";

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if category_id is set
if(isset($_REQUEST['category_id'])) {
    $category_id = $_REQUEST['category_id'];
    
    // Prepare and execute the DELETE statement
    $stmt = $connection->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $category_id);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete Category Record</title>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this category?");
            }
        </script>
    </head>
    <body>
        <form method="post" onsubmit="return confirmDelete();">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($stmt->execute()) {
        echo "Category record deleted successfully.";
    } else {
        echo "Error deleting category record: " . $stmt->error;
    }
    }
?>
</body>
</html>
<?php

    $stmt->close();
} else {
    echo "category_id is not set.";
}

$connection->close();
?>
