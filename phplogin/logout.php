<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 17:55
 */

session_start();
session_destroy(); // Kun käyttäjä painaa logout näppäintä sessio sulkeutuu ja hänet ohjataan kirjautumissivulle.

header('Location: index.html');
?>
