<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 17:46
 */

session_start();

// kommentoi alla oleva if lause pois jos haluat avata selaimeen suoraan tämän sivun.
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html'); // Jos käyttäjä ei ole kirjautunut sisään. Hänet heitetään takaisin kirjautumissivulle.
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

if (isset($_POST['changeDetails'])) {
// Formeista haetut käyttäjä datat.
$changedCity = mysqli_real_escape_string($con, $_POST['changedCity']);

if (empty($changedCity)) {
    echo '<script language="javascript">';
    echo 'alert("City is required")';
    echo '</script>';
    $errorVariable = true;
}

$id = $_SESSION['id'];
$user_check_query = "SELECT * FROM userstable WHERE id='$id'";
$result = mysqli_query($con, $user_check_query);
$user = mysqli_fetch_assoc($result);

if(!$errorVariable){
    $query = "UPDATE userstable SET city='$changedCity' WHERE id='$id'";
    mysqli_query($con, $query);
    echo "<script>alert('Change successful!'); window.location.href='profile.php';</script>";
}

}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body class="profile-body">
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Grand Tourism-o</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="profile.php" class="nabi">Profile</a></li>
            <li><a href="home.php" class="nabi">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
    </div>
</nav>
<div class="content">

    <div class="profileDetails">
        <p>Your account details:</p>
        <table>
            <tr>
                <td>Username:</td>
                <td><?=$_SESSION['name']?></td>
            </tr>
            <tr>
                <td>Your suburb:</td>
                <td><?=$city?></td>
            </tr>
        </table>
    </div>
    <div class="changeUserDetails">
        <form action="profile.php" method="post" class="formarea">
            <h1 id="profileChangeHeader">Change your home suburb</h1>
            <label for="city"></label>
            <input type="text" name="changedCity" placeholder="New suburb" required id="changeCity"><br>
            <input type="submit" value="Change suburb" name="changeDetails" id="changeBtn">
        </form>
    </div>
</div>
</body>
</html>
