<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 03/10/2019
 * Time: 18:13
 */

session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'password';
$DATABASE_NAME = 'testidatabase';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username']) ) {
    die ('Please fill username field');
}

if ($stmt = $con->prepare('SELECT city FROM userstable WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
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
