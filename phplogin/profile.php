<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 17:46
 */

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html'); // Jos käyttäjä ei ole kirjautunut sisään. hänet heitetään takaisin kirjautumissivulle.
    exit();
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'password';
$DATABASE_NAME = 'testidatabase';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Haetaan käyttäjän id:llä hänen asettamaansa kaupunkia.
$stmt = $con->prepare('SELECT city FROM userstable WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($city);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Profile Page</h2>
    <div>
        <p>Your account details are below:</p>
        <table>
            <tr>
                <td>Username:</td>
                <td><?=$_SESSION['name']?></td>
            </tr>
            <tr>
                <td>Your city</td>
                <td><?=$city?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
