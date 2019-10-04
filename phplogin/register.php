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

// Tarkistetaan onko käyttäjä antanut tyhjiä arvoja.
if (empty($username)) {
    array_push($errors, "Username is required");
}
if (empty($password1)) {
    array_push($errors, "Password is required");
}
if ($password1 != $password2) {
    array_push($errors, "The two passwords do not match");
}

$user_check_query = "SELECT * FROM userstable WHERE username='$username'";
$result = mysqli_query($con, $user_check_query);
$user = mysqli_fetch_assoc($result);

//Tarkistetaan löytyykö tietokannasta jo kyseinen käyttäjä nimi.
if ($user) {
    if ($user['username'] === $username) {
        array_push($errors, "Username already exists");
    }
}
    if (count($errors) == 0) { //jos ei löydy virheitä niin lähetetään tietokantaan käyttäjän data.

        $query = "INSERT INTO userstable (username, password, city) 
  			  VALUES('$username', '$password1', '$city')";
        mysqli_query($con, $query);
        $_SESSION['username'] = $username;
        header('location: index.html');
    }
}

?>
<h1>Register</h1>
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" placeholder="Username" id="username" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password1" placeholder="Password" id="password" required><br>
    <label for="password2">Repeat password:</label>
    <input type="password" name="password2" placeholder="Repeat password" id="password2" required><br>
    <label for="city">City</label>
    <input type="text" name="city" placeholder="City" id="city" ><br>
    <input type="submit" value="Register" name="registerUser">
</form>
