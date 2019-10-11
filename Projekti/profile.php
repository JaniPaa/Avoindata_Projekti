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
$stmt = $con->prepare('SELECT city, favorite FROM userstable WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($city, $favorite);
$stmt->fetch();
$stmt->close();

// Jos käyttäjä painaa "Change division" nappia.
if (isset($_POST['changeDetails'])) {
    $changedCity = mysqli_real_escape_string($con, $_POST['changedCity']); // haetaan division formista
    $errorVariable = false;
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

    // jos ei virheitä niin updatetaan käyttäjän division kolumnia.
    if (!$errorVariable) {
        $query = "UPDATE userstable SET city='$changedCity' WHERE id='$id'";
        mysqli_query($con, $query);
        echo "<script>alert('Change successful!'); window.location.href='profile.php';</script>";
    }

}
// Käyttäjän favorite paikan tiedot lähetetään relaatiotietokannan favorites tauluun, kun hän on painanut "Add to favorites" nappia kotisivulla.
if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $email = $_GET['email'];
    $phonenumber = $_GET['phonenumber'];
    $address = $_GET['address'];
    $id = $_SESSION['id'];
    $querySetFavorite = "INSERT INTO favorites (name, user_id, email, phonenumber, address) VALUES ('$name', '$id','$email', '$phonenumber', '$address') ";
    mysqli_query($con, $querySetFavorite);


}
// Jos käyttäjä painaa "Show favorites" buttonia niin käyttäjän favoritet haetaan tietokannasta ja ne laitetaan muuttujaan "obj".
if (isset($_GET['getFavorites'])) {
    $id = $_SESSION['id'];
    $queryGetFavorites = "SELECT * FROM favorites WHERE user_id='$id'"; // Haetaan käyttäjän favoritet favorites taulusta.
    $result = mysqli_query($con, $queryGetFavorites);
    if (mysqli_num_rows($result) == 0) {
        $hello = "Not hello";
    } else {
        $hello = "Hello";
        $obj = mysqli_fetch_all($result, MYSQLI_BOTH);
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
            <a class="navbar-brand" href="home.php">Grand Tourism-o</a>
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
                <td><?= $_SESSION['name'] ?></td>
            </tr>
            <tr>
                <td>Division:</td>
                <td><?= $city ?></td>
            </tr>
        </table>
        <form action="profile.php" method="get">
            <input type="submit" value="Show my favorites" name="getFavorites" id="getFavBtn">
        </form>
    </div>
    <div class="changeUserDetails">
        <form action="profile.php" method="post" class="formarea">
            <h1 id="profileChangeHeader">Change your home Division</h1>
            <label for="city"></label>
            <input type="text" name="changedCity" placeholder="New division" required id="changeCity"><br>
            <input type="submit" value="Change division" name="changeDetails" id="changeBtn">
        </form>
    </div>
    <div id="favDiv">
        <table id="favTable"></table>
    </div>
</div>
<script>
    // muuttujaan obj lähetetään php filulta, joka muutetaan samalla json muotoon.
    var obj = <?php echo json_encode($obj); ?>;
    console.log(obj);
    console.log(obj[1].name + ", " + obj[1].phonenumber + ", " + obj[1].email + ", " + obj[1].address);
    console.log(Object.keys(obj).length);

    var table = document.getElementById('favTable').createCaption();
    table.innerHTML = "Your favorites";
    var trHeader = document.createElement('tr');
    var th1 = document.createElement('th');
    th1.innerHTML = 'Name';
    var th2 = document.createElement('th');
    th2.innerHTML = 'Phonenumber';
    var th4 = document.createElement('th');
    th4.innerHTML = 'Email';
    var th5 = document.createElement('th');
    th5.innerHTML = 'Street Address';
    trHeader.append(th1, th2, th4, th5);
    table.appendChild(trHeader);

    // luupataan obj läpi ja asetetaan saatu data pöytään.
    for (var i = 0; i < Object.keys(obj).length; i++) {
        console.log(obj[i].name);
        var tr = document.createElement('tr');
        var td = document.createElement('td');
        td.innerHTML = obj[i].name;
        var td2 = document.createElement('td');
        td2.innerHTML = obj[i].phonenumber;
        var td3 = document.createElement('td');
        td3.innerHTML = obj[i].email;
        var td4 = document.createElement('td');
        td4.innerHTML = obj[i].address;
        tr.append(td, td2, td3, td4);
        table.appendChild(tr);
    }
</script>
</body>
</html>
