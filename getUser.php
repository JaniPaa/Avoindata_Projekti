<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 03/10/2019
 * Time: 18:13
 */

$mysqli = new mysqli("localhost", "root", "password", "testidatabase");

if($mysqli->connect_error){
    exit('count not connect to database');
}

$sql = "SELECT id, username, password, city FROM userstable WHERE username = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_GET['q']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $user, $pass, $city );
$stmt->fetch();
$stmt->close();

echo "<table>";
echo "<tr>";
echo "<th>User ID</th>";
echo "<td>" . $id . "</td>";
echo "<th>Username</th>";
echo "<td>" . $user . "</td>";
echo "<th>Password</th>";
echo "<td>" . $pass . "</td>";
echo "<th>City</th>";
echo "<td>" . $city . "</td>";
echo "</tr>";
echo "</table>";

?>
