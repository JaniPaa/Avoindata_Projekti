<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 15:53
 */

session_start();

// kommentoi alla oleva if lause pois jos haluat avata selaimeen suoraan tämän sivun.
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html'); // jos käyttäjä ei ole kirjautunut, hänet ohjataan kirjautumissivulle.
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home Page</title>
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
    <h2>Home Page</h2>
    <p>Welcome back, <?=$_SESSION['name']?>!</p>
</div>
<div id="getUserCity">
    <form action="getCity.php" method="post">
        <input type="text" name="username" placeholder="Username" id="username" required>
        <input type="submit" value="getCity">
    </form>
    <div id="txtHint">Tähän tulee userin kaupunki</div>
</div>
<div id="getJobs"></div>

<script>
    var xmlhttp = new XMLHttpRequest();
    var url = "tyontekijat.json"; // 2
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var myArr = JSON.parse(xmlhttp.responseText);
            myFunction(myArr.tyontekijat); // 3
        }
    }
    xmlhttp.open("GET", url, true); // 4
    xmlhttp.send();
    function myFunction(arr) { // 5
        var out = "";
        var i;
        for(i = 0; i < arr.length; i++) {
            out += arr[i].firstName + " " +
                arr[i].lastName + '<br>';
        }
        document.getElementById("duunarit").innerHTML = out; // 6
    }
</script>

</body>
</html>
