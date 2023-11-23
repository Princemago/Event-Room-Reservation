<?php
require 'connect.php';

if(isset($_GET['id'])) {
    $reserveNumber = $_GET['id'];

    // Delete the record based on 'Reserve Number'
    $query = "DELETE FROM main WHERE `Reserve Number` = '$reserveNumber'";
    $result = mysqli_query($conn, $query);

    if($result) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>