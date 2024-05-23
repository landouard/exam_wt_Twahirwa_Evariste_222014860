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

// Check if category_id is set
if (isset($_REQUEST['category_id'])) {
    $category_id = $_REQUEST['category_id'];

    // Use prepared statement
    $stmt = $connection->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $description = htmlspecialchars($row['description'], ENT_QUOTES);
        $parent_id = htmlspecialchars($row['parent_id'], ENT_QUOTES);
    } else {
        echo "Category not found.";
        exit();
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Category</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body><center>
    <!-- Update categories form -->
    <h2><u>Update Form of Categories</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label for="name">Category Name:</label>
        <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
        <br><br>

        <label for="description">Description:</label>
        <input type="text" name="description" value="<?php echo isset($description) ? $description : ''; ?>" required>
        <br><br>

        <label for="parent_id">Parent ID:</label>
        <input type="text" name="parent_id" value="<?php echo isset($parent_id) ? $parent_id : ''; ?>" required>
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
    $parent_id = htmlspecialchars($_POST['parent_id'], ENT_QUOTES);

    // Use prepared statement for update
    $stmt = $connection->prepare("UPDATE categories SET name = ?, description = ?, parent_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $name, $description, $parent_id, $category_id);

    if ($stmt->execute()) {
        // Redirect to categories.php on successful update
        header('Location: categories.php');
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
