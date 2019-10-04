<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 03/10/2019
 * Time: 18:13
 */

session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'password';
$DATABASE_NAME = 'testidatabase';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username']) ) {
    // Could not get the data that should have been sent.
    die ('Please fill username field');
}

if ($stmt = $con->prepare('SELECT city FROM userstable WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
    $stmt->bind_result($city );
    $stmt->fetch();
    $stmt->close();

    echo "<table>";
    echo "<tr>";
    echo "<th>City: </th>";
    echo "<td>" . $city . "</td>";
    echo "</tr>";
    echo "</table>";
}

?>
