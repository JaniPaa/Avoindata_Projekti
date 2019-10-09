<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 17:55
 */

session_start();
session_destroy();

echo "<script>alert('You are logged out.'); window.location.href='index.html';</script>"; // Kun käyttäjä painaa logout näppäintä sessio sulkeutuu ja hänet ohjataan kirjautumissivulle.
?>
