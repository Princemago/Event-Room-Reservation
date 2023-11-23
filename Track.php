<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchMessage = ""; // Variable to store search result message
$search_result = null; // Initialize $search_result to null

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["search_id"])) {
        $search_id = $_POST["search_id"];
        $search_query = "SELECT r.*, v.venue_name, t.teacher_name, t.email as teacher_email, s.student_name, s.email as student_email, d.department_name 
                        FROM reservations r
                        LEFT JOIN venues v ON r.venue_id = v.venue_id
                        LEFT JOIN tbempinfo t ON r.teacher_id = t.teacher_id
                        LEFT JOIN tbstudinfo s ON r.student_id = s.student_id
                        LEFT JOIN departments d ON r.department_id = d.department_id
                        WHERE r.reservation_id = ?";

        $search_stmt = $conn->prepare($search_query);

        if (!$search_stmt) {
            die("Error preparing search statement: " . $conn->error);
        }

        $search_stmt->bind_param("i", $search_id);

        if ($search_stmt->execute()) {
            $search_result = $search_stmt->get_result();

            if (!$search_result) {
                die("Error getting search result: " . $search_stmt->error);
            }

            if ($search_result->num_rows > 0) {
                $searchMessage = "Search results:";
            } else {
                $searchMessage = "No records found for reservation ID: $search_id";
            }
        } else {
            $searchMessage = "Error searching reservation: " . $search_stmt->error;
        }

        $search_stmt->close();
    }
}

// Check if a logout action is triggered
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Include the logout logic here or redirect to your logout script
    // Example: header("Location: logout.php");
    // Ensure that the logout.php file contains the logic to destroy the session
    // and redirect the user to the login page.
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Tracker</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom style to remove card hover effect -->
    <style>
        .card:hover {
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Reservation Tracker</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="search_id">Reservation ID:</label>
                        <input type="text" class="form-control" name="search_id" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="card-footer">
                <div class="search-results mt-4">
                    <?php
                    echo "<p>$searchMessage</p>";

                    if ($search_result && $search_result->num_rows > 0) {
                        while ($row = $search_result->fetch_assoc()) {
                            // Display search results in Bootstrap cards
                            echo "<div class='card mt-3'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>Reservation ID: {$row['reservation_id']}</h5>";
                            echo "<p class='card-text'><strong>Venue:</strong> {$row['venue_name']}</p>";

                            // Check if teacher is not null before displaying
                            if (!is_null($row['teacher_name'])) {
                                echo "<p class='card-text'><strong>Teacher:</strong> {$row['teacher_name']} (Email: {$row['teacher_email']})</p>";
                            }

                            // Check if student is not null before displaying
                            if (!is_null($row['student_name'])) {
                                echo "<p class='card-text'><strong>Student:</strong> {$row['student_name']} (Email: {$row['student_email']})</p>";
                            }

                            echo "<p class='card-text'><strong>Department:</strong> {$row['department_name']}</p>";
                            echo "<p class='card-text'><strong>Date and Time:</strong> " . date('Y-m-d H:i:s', strtotime($row['start_time'])) . "</p>";
                            echo "<p class='card-text'><strong>Status:</strong> {$row['status']}</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
                <!-- Add the logout button -->
                <div class="text-right mt-3">
                    <form action="?logout=1" method="post">
                        <a type="submit" class="btn btn-danger" href="studentLogin.php">Logout</a>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Bootstrap JS and Popper.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
