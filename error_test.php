<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Error reporting is now enabled<br>";

include "db.php";
echo "Database connection successful<br>";

$result = mysqli_query($conn, "SELECT 1");
if($result) {
    echo "Database query working<br>";
} else {
    echo "Database query failed: " . mysqli_error($conn);
}
?>