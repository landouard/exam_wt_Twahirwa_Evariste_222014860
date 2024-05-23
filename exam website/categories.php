<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Category Info</title>
  <!-- Include your CSS and JavaScript files here -->
</head>
<body style="background-color: lightblue;">
  <header>
    <!-- Your header content here -->
  </header>
  <body style="background-color: yellowgreen;">
    <h1>Categories Form</h1>
    <form method="post" onsubmit="return confirmInsert();">
      <label for="category_id">Category ID:</label>
      <input type="number" id="category_id" name="category_id" required><br><br>

      <label for="category_name">Category Name:</label>
      <input type="text" id="category_name" name="category_name" required><br><br>

      <label for="description">Description:</label>
      <input type="text" id="description" name="description" required><br><br>

      <label for="parent_id">Parent ID:</label>
      <input type="number" id="parent_id" name="parent_id"><br><br>

      <input type="submit" name="add" value="Insert"><br><br>

      <a href="./home.html">Go Back to Home</a>
    </form>

    <?php
    include('database_connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $category_id = $_POST['category_id'];
        $category_name = $_POST['category_name'];
        $description = $_POST['description'];
        $parent_id = $_POST['parent_id'];

        $stmt = $connection->prepare("INSERT INTO categories (id, name, description, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $category_id, $category_name, $description, $parent_id);

        if ($stmt->execute()) {
            echo "New record has been added successfully.<br><br><a href='categories.php'>Back to Form</a>";
        } else {
            echo "Error inserting data: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>

    <section>
      <h2>Categories Detail</h2>
      <table>
        <tr>
          <th>Category ID</th>
          <th>Category Name</th>
          <th>Description</th>
          <th>Parent ID</th>
          <th>Delete</th>
          <th>Update</th>
        </tr>
        <?php
        $sql = "SELECT * FROM categories";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['parent_id']}</td>
                        <td><a style='padding:4px' href='delete_category.php?category_id={$row['id']}'>Delete</a></td> 
                        <td><a style='padding:4px' href='update_category.php?category_id={$row['id']}'>Update</a></td> 
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        ?>
      </table>
    </section>

    <footer>
      <!-- Your footer content here -->
    </footer>
  </body>
</html>
