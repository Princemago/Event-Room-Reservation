<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$deleteMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_id"])) {
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM reservations WHERE reservation_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            $deleteMessage = "Record deleted successfully";
        } else {
            $deleteMessage = "Error deleting record: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } elseif (isset($_POST["approve_id"])) {
        $approve_id = $_POST["approve_id"];
        $approve_query = "UPDATE reservations SET status = 'approved' WHERE reservation_id = ?";
        $approve_stmt = $conn->prepare($approve_query);
        $approve_stmt->bind_param("i", $approve_id);

        if ($approve_stmt->execute()) {
            $deleteMessage = "Reservation approved successfully";
        } else {
            $deleteMessage = "Error approving reservation: " . $approve_stmt->error;
        }

        $approve_stmt->close();
    } elseif (isset($_POST["disapprove_id"])) {
        $disapprove_id = $_POST["disapprove_id"];
        $disapprove_query = "UPDATE reservations SET status = 'disapproved' WHERE reservation_id = ?";
        $disapprove_stmt = $conn->prepare($disapprove_query);
        $disapprove_stmt->bind_param("i", $disapprove_id);

        if ($disapprove_stmt->execute()) {
            $deleteMessage = "Reservation disapproved successfully";
        } else {
            $deleteMessage = "Error disapproving reservation: " . $disapprove_stmt->error;
        }

        $disapprove_stmt->close();
    } else {
        $venueID = $_POST["venue_id"];
        $teacherSrcode = empty($_POST["teacher_srcode"]) ? null : $_POST["teacher_srcode"];
        $studentSrcode = empty($_POST["student_srcode"]) ? null : $_POST["student_srcode"];
        $departmentID = $_POST["department"];
        $dateAndTime = $_POST["date_and_time"];

        $sql = "INSERT INTO reservations (venue_id, teacher_id, student_id, department_id, start_time) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiis", $venueID, $teacherSrcode, $studentSrcode, $departmentID, $dateAndTime);

        if ($stmt->execute()) {
            $deleteMessage = "Record added successfully";
        } else {
            $deleteMessage = "Error adding record: " . $stmt->error;
            $deleteMessage .= " SQL: " . $sql;
        }

        $stmt->close();
    }
}

$select_query = "SELECT r.*, v.venue_name, t.teacher_name, s.student_name, d.department_name 
                 FROM reservations r
                 LEFT JOIN venues v ON r.venue_id = v.venue_id
                 LEFT JOIN tbempinfo t ON r.teacher_id = t.teacher_id
                 LEFT JOIN tbstudinfo s ON r.student_id = s.student_id
                 LEFT JOIN departments d ON r.department_id = d.department_id";
$result = $conn->query($select_query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        #hiddenContent {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <ul class="navigation">
            <li>
                <a href="indexView.php">
                    <i class="bi bi-house-door-fill"></i> 
                    <span class="dashboard">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="toggleContent()">
                    <i class="bi bi-person-workspace"></i>
                    <span class="dashboard">Reports</span>
                </a>
            </li>
            <li class="logout">
                <a href="loginViewer.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="dashboard">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="cardcontainer">
            <h3>Welcome Back! Manage schedule and check the vacant rooms.</h3>
            <hr>
            <h3>Reservation List</h3>

            <table border="1">
                <tr>
                    <th>Venue</th>
                    <th>Teacher</th>
                    <th>Student</th>
                    <th>Department</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                </tr>

                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["venue_name"] . "</td>";
                        echo "<td>" . $row["teacher_name"] . "</td>";
                        echo "<td>" . $row["student_name"] . "</td>";
                        echo "<td>" . $row["department_name"] . "</td>";
                        echo "<td>" . date('Y-m-d H:i:s', strtotime($row["start_time"])) . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>

        <div id="hiddenContent">
            <form action="" method="post">
                <label for="venue_id">Venue:</label>
                <select name="venue_id" required>
                    <?php
                    $venue_query = "SELECT venue_id, venue_name FROM venues";
                    $venue_result = $conn->query($venue_query);

                    while ($venue_row = $venue_result->fetch_assoc()) {
                        echo "<option value='" . $venue_row["venue_id"] . "'>" . $venue_row["venue_name"] . "</option>";
                    }
                    ?>
                </select><br>

                <label for="teacher_srcode">Teacher:</label>
                <select name="teacher_srcode">
                    <option value="">Select teacher</option>
                    <?php
                    $teacher_query = "SELECT teacher_id, teacher_name FROM tbempinfo";
                    $teacher_result = $conn->query($teacher_query);

                    while ($teacher_row = $teacher_result->fetch_assoc()) {
                        echo "<option value='" . $teacher_row["teacher_id"] . "'>" . $teacher_row["teacher_name"] . "</option>";
                    }
                    ?>
                </select><br>

                <label for="student_srcode">Student:</label>
                <select name="student_srcode">
                    <option value="">Select student</option>
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
        </div>
    </div>

    <script>
        function toggleContent() {
            var content = document.getElementById("hiddenContent");
            if (content.style.display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>
</body>
</html>
