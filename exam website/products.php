 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Workerinfo</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* CSS styles for consistent styling */
    a:link {
      color: #0066cc;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    a {
      padding: 7px;
      color: white;
      background-color: turquoise;
      text-decoration: none;
      margin-right: 5px;
    }

    a:visited {
      color: purple;
    }

    a:link {
      color: brown;
    }

    a:hover {
      background-color: white;
    }

    a:active {
      background-color: red;
    }

    button.btn {
      margin-left: 15px; 
      margin-top: 7px;
    }

    input.form-control {
      padding: 10px;
    }

    table {
      width: 75%;
      border-collapse: collapse;
    }

    th, td {
      border: 2px solid black;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: orange;
    }

    section {
      padding: 20px; 
      border-bottom: 3px solid #ddd;
    }

    footer {
      text-align: center; 
      padding: 10px; 
      background-color: darkgray;
    }
  </style>
  <script>
    function confirmInsert() {
      return confirm("Are you sure you want to insert this record?");
    }
  </script>
</head>
<body style="background-color: lightblue;">
  <header>
    <ul style="list-style-type: none; padding: 0;">
      <li style="display: inline; margin-right: 10px;">
        <ul style="list-style-type: none; padding: 0;">
          <li style="display: inline; margin-right: 8px;"><a href="./home.html">HOME</a></li>
          <li style="display: inline; margin-right: 10px;"><a href="./about.html">ABOUT</a></li>
          <li style="display: inline; margin-right: 10px;"><a href="./contact.html">CONTACT</a></li>
           <li class="dropdown" style="display: inline; margin-right: 5px;">
            <a href="#" style="padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 10px;">tables</a>
            <div class="dropdown-contents">
           <a href="./Courses.php">COURSES</a>
          <a href="./coursemodules.php">COURSEMODULE</a>
          <a href="./enrollments.php">ENROLLMENTS</a>
          <a href="./instructors.php"> INSTRUCTORS</a>
          <a href="./debtmanagementresources.php">DEBTMANAGEMENTRESOURCES</a>
          <a href="./payments.php">PAYMENTS</a>
          <a href="./moduleresources.php">moduleresources</a>
          <a href="./quizattempts.php">QUIZATTEMPS</a>
          <a href="./quizzes.php">QUIZZES</a>
          <a href="./users.php">USERS</a>
            </div>
          </li>
              <a href="logout.php">Logout</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </header>
  <body style="background-color: yellowgreen;">
    <h1>Courses Form</h1>
    <form method="post" onsubmit="return confirmInsert();">
      <label for="course_id">Course ID:</label>
      <input type="number" id="course_id" name="course_id" required><br><br>

      <label for="course_name">Course Name:</label>
      <input type="text" id="course_name" name="course_name" required><br><br>

      <label for="instructor_id">Instructor ID:</label>
      <input type="number" id="instructor_id" name="instructor_id" required><br><br>

      <label for="description">Description:</label>
      <input type="text" id="description" name="description" required><br><br>

      <label for="start_date">Start Date:</label>
      <input type="date" id="start_date" name="start_date" required><br><br>

      <label for="end_date">End Date:</label>
      <input type="date" id="end_date" name="end_date" required><br><br>

      <label for="price">Price:</label>
      <input type="number" id="price" name="price" required><br><br>

      <label for="status">Status:</label>
      <input type="text" id="status" name="status" required><br><br>

      <input type="submit" name="add" value="Insert"><br><br>

      <a href="./home.html">Go Back to Home</a>
    </form>

    <?php
    include('database_connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $course_id = $_POST['course_id'];
        $course_name = $_POST['course_name'];
        $instructor_id = $_POST['instructor_id'];
        $description = $_POST['description'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $price = $_POST['price'];
        $status = $_POST['status'];

        $stmt = $connection->prepare("INSERT INTO courses (course_id, course_name, instructor_id, description, start_date, end_date, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissdss", $course_id, $course_name, $instructor_id, $description, $start_date, $end_date, $price, $status);

        if ($stmt->execute()) {
            echo "New record has been added successfully.<br><br><a href='courses.php'>Back to Form</a>";
        } else {
            echo "Error inserting data: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>

    <section>
      <h2>Courses Detail</h2>
      <table>
        <tr>
          <th>Course ID</th>
          <th>Course Name</th>
          <th>Instructor ID</th>
          <th>Description</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Price</th>
          <th>Status</th>
          <th>Delete</th>
          <th>Update</th>
        </tr>
        <?php
        $sql = "SELECT * FROM courses";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['course_id']}</td>
                        <td>{$row['course_name']}</td>
                        <td>{$row['instructor_id']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['start_date']}</td>
                        <td>{$row['end_date']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['status']}</td>
                        <td><a style='padding:4px' href='delete_course.php?course_id={$row['course_id']}'>Delete</a></td> 
                        <td><a style='padding:4px' href='update_course.php?course_id={$row['course_id']}'>Update</a></td> 
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No data found</td></tr>";
        }
        ?>
      </table>
    </section>

    <footer>
      <h2>UR CBE BIT &copy; 2024 &reg; Designed by: @murara</h2>
    </footer>

  </body>
</html>
