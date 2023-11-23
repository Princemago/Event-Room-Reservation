<?php
require 'connect.php';

if(isset($_GET['id'])) {
    $reserveNumber = $_GET['id'];

    // Fetch the record based on 'Reserve Number'
    $query = "SELECT * FROM main WHERE `Reserve Number` = '$reserveNumber'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);

        // Display the form with the fetched data
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Record</title>
        </head>
        <body>
            <h2>Edit Record</h2>
            <form method="POST" action="update.php">
                <input type="hidden" name="reserveNumber" value="<?= $record['Reserve Number'] ?>">
                <label>Department ID:</label>
                <input type="text" name="department" value="<?= $record['Department ID'] ?>">
                <br><br>
                <label>Venue ID:</label>
                <input type="text" name="venue" value="<?= $record['Venue ID'] ?>">
                <br><br>
                <label>Teacher Srcode:</label>
                <input type="text" name="teacher" value="<?= $record['Teacher Srcode'] ?>">
                <br><br>
                <label>Student Srcode:</label>
                <input type="text" name="student" value="<?= $record['Student Srcode'] ?>">
                <br><br>
                <label>Date and Time</label>
                <input type="text" name="datetime" value="<?= $record['Date and Time'] ?>">
                <br>
                <input type="submit" name="submit" value="Update">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid request.";
}
?>