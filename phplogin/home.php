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
    <title>Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Home Page</h2>
    <p>Welcome, <?=$_SESSION['name']?>!</p>
</div>
<div id="getUserCity">
    <div id="txtHint">Your suburb: <?=$city?></div>
</div>
<div id="getEvents">
    <table id="eventsTable"></table>
    <button onclick="createEventTable(jsonJobData)">Get events with home suburb</button>
    <table id="eventsTable2"></table>
    <form>
        <input type="text" name="username" placeholder="Suburb" id="suburb" required>
    </form>
    <button id="textEventButton" onclick="getEventsViaText()">Get Events</button>
</div>

<script>
    var jsonEvents;
    var phpCity = "<?=$city?>";
    var jsonJobData;
    var xmlhttp = new XMLHttpRequest();
    var url = `http://api.hel.fi/linkedevents/v1/place/?text=${phpCity}`;

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            jsonJobData = JSON.parse(xmlhttp.responseText);
            console.log(jsonJobData);
        }
    };
    xmlhttp.open('GET', url, true); // 4
    xmlhttp.send();

    function createEventTable(jsonJobData){
        document.getElementById('eventsTable').innerHTML = "";
        var table = document.getElementById('eventsTable');
        var trHeader = document.createElement('tr');
        var th1 = document.createElement('th');
        th1.innerHTML = 'Name';
        var th2 = document.createElement('th');
        th2.innerHTML = 'Info url';
        var th3 = document.createElement('th');
        th3.innerHTML = 'Description';
        var th4 = document.createElement('th');
        th4.innerHTML = 'Email';
        trHeader.append(th1, th2, th3, th4);
        table.appendChild(trHeader);

        var i;
        for (i = 0; i < Object.keys(jsonJobData.data).length; i++) {
            console.log(jsonJobData.data[i].info_url.fi);
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            td.innerHTML = jsonJobData.data[i].name.fi;
            var td2 = document.createElement('td');
            td2.innerHTML = '<a href="'+jsonJobData.data[i].info_url.fi+'">'+jsonJobData.data[i].info_url.fi+'</a>';
            var td3 = document.createElement('td');
            if(jsonJobData.data[i].description != null){
                td3.innerHTML = jsonJobData.data[i].description.fi;
            }else{
                td3.innerHTML = "Ei tietoja saatavilla.";
            }
            var td4 = document.createElement('td');
            if(jsonJobData.data[i].email != null){
                td4.innerHTML = jsonJobData.data[i].email;
            }else{
                td4.innerHTML = "Ei tietoja saatavilla.";
            }

            tr.append(td, td2, td3, td4);
            table.appendChild(tr);
        }
    }

    function getEventsViaText(){
        var suburb = document.getElementById('suburb').value;
        console.log(`http://api.hel.fi/linkedevents/v1/place/?text=${suburb}`);
        fetch(`http://api.hel.fi/linkedevents/v1/place/?text=${suburb}`)
            .then(function(resp) { return resp.json() }) // Convert data to json
            .then(function(data) {
                console.log(suburb);
                console.log(data);
                jsonEvents = data;
            })
            .catch(function() {
                // catch any errors
            });

        document.getElementById('eventsTable2').innerHTML = "";
        var table = document.getElementById('eventsTable2');
        var trHeader = document.createElement('tr');
        var th1 = document.createElement('th');
        th1.innerHTML = 'Name';
        var th2 = document.createElement('th');
        th2.innerHTML = 'Info url';
        var th3 = document.createElement('th');
        th3.innerHTML = 'Description';
        var th4 = document.createElement('th');
        th4.innerHTML = 'Email';
        trHeader.append(th1, th2, th3, th4);
        table.appendChild(trHeader);

        var i;
        for (i = 0; i < Object.keys(jsonEvents.data).length; i++) {
            console.log(jsonEvents.data[i].info_url.fi);
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            td.innerHTML = jsonEvents.data[i].name.fi;
            var td2 = document.createElement('td');
            td2.innerHTML = '<a href="'+jsonEvents.data[i].info_url.fi+'">'+jsonEvents.data[i].info_url.fi+'</a>';
            var td3 = document.createElement('td');
            if(jsonEvents.data[i].description != null){
                td3.innerHTML = jsonEvents.data[i].description.fi;
            }else{
                td3.innerHTML = "Ei tietoja saatavilla.";
            }
            var td4 = document.createElement('td');
            if(jsonEvents.data[i].email != null){
                td4.innerHTML = jsonEvents.data[i].email;
            }else{
                td4.innerHTML = "Ei tietoja saatavilla.";
            }

            tr.append(td, td2, td3, td4);
            table.appendChild(tr);
        }
    }
</script>

</body>
</html>
