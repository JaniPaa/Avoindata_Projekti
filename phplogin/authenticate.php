<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 12:05
 */

session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'password';
$DATABASE_NAME = 'testidatabase';

$username = "";
$errors = array();

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
    die ('Täytäthän username ja password kentät!');
}

// Haetaan käyttäjän usernamella hänen id ja salasana.
if ($stmt = $con->prepare('SELECT id, password FROM userstable WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}

$stmt->store_result();
// Tarkistetaan tuliko mitään dataa.
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();
// Verrataan tietokannan salasanaa käyttäjän antamaan salasanaan
    if ($_POST['password'] === $password) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        header('Location: home.php'); // ohjataan käyttäjä kotisivulle jos salasana täsmää
    } else {
        echo 'Incorrect password!';
    }
} else {
    echo 'Incorrect username!';
}
$stmt->close();
?>

