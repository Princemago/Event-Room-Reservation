<?php
// try.php

// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission or data addition here
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the delete button is clicked
    if (isset($_POST["delete_id"])) {
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM reservations WHERE reservation_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } else {
        // Retrieve data from the form
        $venueID = $_POST["venue_id"];
        $teacherSrcode = $_POST["teacher_srcode"];
        $studentSrcode = $_POST["student_srcode"];
        $departmentID = $_POST["department"];
        $dateAndTime = $_POST["date_and_time"];

        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO reservations (venue_id, teacher_id, student_id, department_id, start_time) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiis", $venueID, $teacherSrcode, $studentSrcode, $departmentID, $dateAndTime);

        if ($stmt->execute()) {
            echo "Record added successfully";
        } else {
            echo "Error adding record: " . $stmt->error;
            // Output the SQL query for debugging
            echo "SQL: " . $sql;
        }

        $stmt->close();
    }
}

// Fetch and display data from the 'reservations' table
$select_query = "SELECT r.*, v.venue_name, t.teacher_name, s.student_name, d.department_name 
                 FROM reservations r
                 JOIN venues v ON r.venue_id = v.venue_id
                 JOIN tbempinfo t ON r.teacher_id = t.teacher_id
                 JOIN tbstudinfo s ON r.student_id = s.student_id
                 JOIN departments d ON r.department_id = d.department_id";
$result = $conn->query($select_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record Form</title>
</head>
<body>

<h2>Add Record Form</h2>

<!-- Add Record Form -->
<form action="" method="post">
    <label for="venue_id">Venue:</label>
    <select name="venue_id" required>
        <!-- Populate options dynamically based on data from the 'venues' table -->
        <?php
        $venue_query = "SELECT venue_id, venue_name FROM venues";
        $venue_result = $conn->query($venue_query);

        while ($venue_row = $venue_result->fetch_assoc()) {
            echo "<option value='" . $venue_row["venue_id"] . "'>" . $venue_row["venue_name"] . "</option>";
        }
        ?>
    </select><br>

    <label for="teacher_srcode">Teacher:</label>
    <select name="teacher_srcode" required>
        <!-- Populate options dynamically based on data from the 'tbempinfo' table -->
        <?php
        $teacher_query = "SELECT teacher_id, teacher_name FROM tbempinfo";
        $teacher_result = $conn->query($teacher_query);

        while ($teacher_row = $teacher_result->fetch_assoc()) {
            echo "<option value='" . $teacher_row["teacher_id"] . "'>" . $teacher_row["teacher_name"] . "</option>";
        }
        ?>
    </select><br>

    <label for="student_srcode">Student:</label>
    <select name="student_srcode" required>
        <!-- Populate options dynamically based on data from the 'tbstudinfo' table -->
        <?php
        $student_query = "SELECT student_id, student_name FROM tbstudinfo";
        $student_result = $conn->query($student_query);

        while ($student_row = $student_result->fetch_assoc()) {
            echo "<option value='" . $student_row["student_id"] . "'>" . $student_row["student_name"] . "</option>";
        }
        ?>
    </select><br>

    <label for="department">Department:</label>
    <select name="department" required>
        <!-- Populate options dynamically based on data from the 'departments' table -->
        <?php
        $department_query = "SELECT department_id, department_name FROM departments";
        $department_result = $conn->query($department_query);

        while ($department_row = $department_result->fetch_assoc()) {
            echo "<option value='" . $department_row["department_id"] . "'>" . $department_row["department_name"] . "</option>";
        }
        ?>
    </select><br>

    <label for="date_and_time">Date and Time:</label>
    <input type="datetime-local" name="date_and_time" required><br>

    <input type="submit" value="Add Record">
</form>

<table border="1">
    <tr>
        <th>Venue</th>
        <th>Teacher</th>
        <th>Student</th>
        <th>Department</th>
        <th>Date and Time</th>
        <th>Action</th> <!-- New column for delete button -->
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["venue_name"] . "</td>";
            echo "<td>" . $row["teacher_name"] . "</td>";
            echo "<td>" . $row["student_name"] . "</td>";
            echo "<td>" . $row["department_name"] . "</td>";
            echo "<td>" . $row["start_time"] . "</td>";
            // Add a delete button with a form for each row
            echo "<td>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='delete_id' value='" . $row["reservation_id"] . "'>";
            echo "<input type='submit' value='Delete' onclick='return confirm(\"Are you sure?\")'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found</td></tr>";
    }
    ?>

</table>

</body>
</html>