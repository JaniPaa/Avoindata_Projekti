<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 15:53
 */

session_start();

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body class="home-body">
<nav class="navbar navbar-inverse navbar-fixed-top" id="mNavbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Grand Tourism-o</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="profile.php" class="nabi">Profile</a></li>
            <li class="active"><a href="home.php" class="nabi">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <?=$_SESSION['name']?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
        <div class="col-sm-3 col-md-3 pull-right">
            <form class="navbar-form" role="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="srch-term" id="searchArea">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit" onclick="getEventsViaText()"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
    </div>
</nav>
<div class="home-content">
    <h2>Home Pae</h2>
    <p>You can search events happening inside Helsinki with your home Helsinki suburb by pressing "Get events with home division" button,
        <br> or by inserting one into the search text field and clicking magnifying glass icon. Alternatively you can search events with keywords like "koulu" or "taide".<br>
        This may take few clicks!
</div>
<div id="getEvents">
    <table id="eventsTable"></table>
    <button onclick="createEventTable(jsonEventData)" id="suburbEventBtn">Get events with home division</button>
    <form>
        <input type="text" name="username" placeholder="Search" id="suburbTextArea" required>
    </form>
    <button id="textEventButton" onclick="getEventsViaText()">Get events</button>
    <table id="eventsTable2"></table>
</div>

<script>
    var jsonEvents;
    var phpCity = "<?=$city?>";
    var jsonEventData;
    var xmlhttp = new XMLHttpRequest();
    var url = `http://api.hel.fi/linkedevents/v1/place/?division=${phpCity}`; // Palauttaa esikaupunki muuttujalla dataa

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            jsonEventData = JSON.parse(xmlhttp.responseText);
            console.log(jsonEventData);
        }
    };
    xmlhttp.open('GET', url, true); // 4
    xmlhttp.send();

    function createEventTable(jsonEventData){
        document.getElementById('eventsTable').innerHTML = "";
        var table = document.getElementById('eventsTable').createCaption();
        table.innerHTML = "Searched with: "+ phpCity;
        var trHeader = document.createElement('tr');
        var th1 = document.createElement('th');
        th1.innerHTML = 'Name';
        var th2 = document.createElement('th');
        th2.innerHTML = 'Phonenumber';
        var th3 = document.createElement('th');
        th3.innerHTML = 'Description';
        var th4 = document.createElement('th');
        th4.innerHTML = 'Email';
        var th5 = document.createElement('th');
        th5.innerHTML = 'Street Address';
        var th6 = document.createElement('th');
        th6.innerHTML = 'Site url';
        trHeader.append(th1, th2, th3, th4, th5, th6);
        table.appendChild(trHeader);

        //Täyttää pöydän json datalla
        var i;
        for (i = 0; i < Object.keys(jsonEventData.data).length; i++) {
            console.log(jsonEventData.data[i].info_url.fi);
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            td.innerHTML = jsonEventData.data[i].name.fi;
            var td2 = document.createElement('td');
            td2.innerHTML = jsonEventData.data[i].telephone.fi;
            var td3 = document.createElement('td');
            if(jsonEventData.data[i].description != null){
                td3.innerHTML = jsonEventData.data[i].description.fi;
            }else{
                td3.innerHTML = "No information available.";
            }
            var td4 = document.createElement('td');
            if(jsonEventData.data[i].email != null){
                td4.innerHTML = jsonEventData.data[i].email;
            }else{
                td4.innerHTML = "No information available.";
            }
            var td5 = document.createElement('td');
            td5.innerHTML = jsonEventData.data[i].street_address.fi;
            var td6 = document.createElement('td');
            td6.innerHTML = '<a href="'+jsonEventData.data[i].info_url.fi+'">'+jsonEventData.data[i].info_url.fi+'</a>';
            var tdBtn1 = document.createElement('td');
            tdBtn1.innerHTML = `<input type="button" style="color:black" onclick="getData(this)" value="Add to favourite"</input>`;
            tr.append(td, td2, td3, td4, td5, td6, tdBtn1);
            table.appendChild(tr);
        }
    }

    // Haetaan APi kutsulla tietoa tapahtumista millä tahansa teksti parametrilla.
    function getEventsViaText(){
        var suburb = document.getElementById('searchArea').value;
        console.log(`http://api.hel.fi/linkedevents/v1/place/?text=${suburb}`);

        fetch(`http://api.hel.fi/linkedevents/v1/place/?text=${suburb}`) // palauttaa millä tahansa muuttujalla dataa, joka täsmää johonkin nimeen API:ssa.
            .then(function(resp) { return resp.json() })
            .then(function(data) {
                console.log(suburb);
                console.log(data);
                jsonEvents = data;
            })
            .catch(function() {

            });

        document.getElementById('eventsTable2').innerHTML = "";
        var table = document.getElementById('eventsTable2');
        var trHeader = document.createElement('tr');
        var th1 = document.createElement('th');
        th1.innerHTML = 'Name';
        var th2 = document.createElement('th');
        th2.innerHTML = 'Phonenumber';
        var th3 = document.createElement('th');
        th3.innerHTML = 'Description';
        var th4 = document.createElement('th');
        th4.innerHTML = 'Email';
        var th5 = document.createElement('th');
        th5.innerHTML = 'Street Address';
        var th6 = document.createElement('th');
        th6.innerHTML = 'Site url';
        trHeader.append(th1, th2, th3, th4, th5, th6);
        table.appendChild(trHeader);

        // täyttää pöydän saadulla json datalla
        var i;
        for (i = 0; i < Object.keys(jsonEvents.data).length; i++) {
            console.log(jsonEvents.data[i].info_url.fi);
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            td.innerHTML = jsonEvents.data[i].name.fi;
            var td2 = document.createElement('td');
            td2.innerHTML = jsonEvents.data[i].telephone.fi;
            var td3 = document.createElement('td');
            if(jsonEvents.data[i].description != null){
                td3.innerHTML = jsonEvents.data[i].description.fi;
            }else{
                td3.innerHTML = "No information available.";
            }
            var td4 = document.createElement('td');
            if(jsonEvents.data[i].email != null){
                td4.innerHTML = jsonEvents.data[i].email;
            }else{
                td4.innerHTML = "No information available.";
            }
            var td5 = document.createElement('td');
            td5.innerHTML = jsonEvents.data[i].street_address.fi;
            var td6 = document.createElement('td');
            td6.innerHTML = '<a href="'+jsonEvents.data[i].info_url.fi+'">'+jsonEvents.data[i].info_url.fi+'</a>';
            var tdBtn2 = document.createElement('td');
            tdBtn2.innerHTML = `<input type="button" style="color:black" onclick="getData(this)" value="Add to favourite"</input>`;
            tr.append(td, td2, td3, td4, td5, td6, tdBtn2);
            table.appendChild(tr);
        }

    }

    // lähettaa datan profile.php:lle kun käyttäjä on painanut favorite buttonia.
    function getData(x) {
        alert(x.parentNode.parentNode.childNodes[0].innerHTML + " is added to your favorites.");
        var name = x.parentNode.parentNode.childNodes[0].innerHTML;
        var email = x.parentNode.parentNode.childNodes[3].innerHTML;
        var address = x.parentNode.parentNode.childNodes[4].innerHTML;
        var phonenumber = x.parentNode.parentNode.childNodes[1].innerHTML;
        window.location.href = "profile.php?name="+name+"&email="+email+"&phonenumber="+phonenumber+"&address="+address;
    }
</script>

</body>
</html>
