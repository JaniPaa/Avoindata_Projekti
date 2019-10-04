function showCustomer(str) {
    alert('täällä');
    var xhttp;
    if (str === "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "getCity.php?q="+str, true);
    xhttp.send();
}
