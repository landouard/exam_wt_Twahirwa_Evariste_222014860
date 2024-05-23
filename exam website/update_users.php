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
if (isset($_REQUEST['user_id'])) {
    $user_id = $_REQUEST['user_id'];

    // Use prepared statement
    $stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = htmlspecialchars($row['username'], ENT_QUOTES);
        $email = htmlspecialchars($row['email'], ENT_QUOTES);
        $password = htmlspecialchars($row['password'], ENT_QUOTES);
        $registration_date = htmlspecialchars($row['registration_date'], ENT_QUOTES);
    } else {
        echo "User not found.";
        exit();
    }

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body><center>
    <!-- Update users form -->
    <h2><u>Update Form of Users</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?php echo isset($password) ? $password : ''; ?>" required>
        <br><br>

        <label for="registration_date">Registration Date:</label>
        <input type="date" name="registration_date" value="<?php echo isset($registration_date) ? $registration_date : ''; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>

</body>
</html>

<?php
// Handle form submission
if (isset($_POST['up'])) {
    // Retrieve updated values from the form
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    $registration_date = htmlspecialchars($_POST['registration_date'], ENT_QUOTES);

    // Use prepared statement for update
    $stmt = $connection->prepare("UPDATE users SET username = ?, email = ?, password = ?, registration_date = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $password, $registration_date, $user_id);

    if ($stmt->execute()) {
        // Redirect to users.php on successful update
        header('Location: users.php');
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
