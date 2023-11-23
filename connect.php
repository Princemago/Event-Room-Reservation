<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
// Handle the form submission or data addition here
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form or another source
    $departmentID = $_POST["department_id"];
    $venueID = $_POST["venue_id"];
    $teacherSrcode = $_POST["teacher_srcode"];
    $studentSrcode = $_POST["student_srcode"];
    $dateAndTime = $_POST["date_and_time"];

    // Insert data into the 'main' table
    $sql = "INSERT INTO main (Department ID, Venue ID, Teacher Srcode, Student Srcode, Date and Time) 
            VALUES ('$departmentID', '$venueID', '$teacherSrcode', '$studentSrcode', '$dateAndTime')";

    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
    } else {
        echo "Error adding record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
