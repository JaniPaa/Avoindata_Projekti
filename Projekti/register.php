<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 21:13
 */
session_start();

$username = "";
$errors = array();

$con = mysqli_connect('localhost', 'root', 'password', 'testidatabase');
if ( mysqli_connect_errno() ) {
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_POST['registerUser'])) {
// Formeista haetut käyttäjä datat.
$username = mysqli_real_escape_string($con, $_POST['username']);
$password1 = mysqli_real_escape_string($con, $_POST['password1']);
$password2 = mysqli_real_escape_string($con, $_POST['password2']);
$city = mysqli_real_escape_string($con, $_POST['city']);
$favorite = "none";
// Tarkistetaan onko käyttäjä antanut tyhjiä arvoja.
if (empty($username)) {
    array_push($errors, "Username is required");
    echo '<script language="javascript">';
    echo 'alert("Username is required")';
    echo '</script>';
}
if (empty($password1)) {
    array_push($errors, "Password is required");
    echo '<script language="javascript">';
    echo 'alert("Password is required")';
    echo '</script>';
}
if ($password1 != $password2) {
    array_push($errors, "The two passwords do not match");
    echo '<script language="javascript">';
    echo 'alert("The two passwords do not match")';
    echo '</script>';
}
// haetaan asetetun käyttäjä nimen perusteella tietokannasta tietoa.
$user_check_query = "SELECT * FROM userstable WHERE username='$username'";
$result = mysqli_query($con, $user_check_query);
$user = mysqli_fetch_assoc($result);

//Tarkistetaan löytyykö tietokannasta jo kyseinen käyttäjä nimi.
if ($user) {
    if ($user['username'] == $username) {
        array_push($errors, "Username already exists");
        echo '<script language="javascript">';
        echo 'alert("Username already exists")';
        echo '</script>';
    }
}
//jos ei löydy virheitä niin lähetetään tietokantaan käyttäjän data.
    if (count($errors) == 0) {
        $query = "INSERT INTO userstable (username, password, city, favorite) 
  			  VALUES('$username', '$password1', '$city', '$favorite')";
        mysqli_query($con, $query);
        $_SESSION['username'] = $username;
        echo "<script>alert('Registration successful!'); window.location.href='index.html';</script>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body class="register-body">
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Grand Tourism-o</a>
        </div>
        <ul class="nav navbar-nav"></ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="index.html"><span class="glyphicon glyphicon-user"></span> Login</a></li>
        </ul>
    </div>
</nav>
<form action="register.php" method="post" class="testi">
    <h1 id="registerH1">Register</h1>
    <label for="username" class="registerLabel">Username:</label><br>
    <input type="text" name="username" placeholder="Username" id="username" required><br>
    <label for="password"class="registerLabel">Password:</label><br>
    <input type="password" name="password1" placeholder="Password" id="password" required><br>
    <label for="password2"class="registerLabel">Repeat password:</label><br>
    <input type="password" name="password2" placeholder="Repeat password" id="password2" required><br>
    <label for="city"class="registerLabel">Your home Helsinki division:<br>Optional</label><br>
    <input type="text" name="city" placeholder="Division" id="city" ><br>
    <input type="submit" value="Register" name="registerUser" id="submitBtn">
</form>
</body>
</html>
