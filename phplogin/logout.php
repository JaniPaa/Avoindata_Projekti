<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 04/10/2019
 * Time: 17:55
 */

session_start();
session_destroy();

header('Location: index.html'); // Kun käyttäjä painaa logout näppäintä sessio sulkeutuu ja hänet ohjataan kirjautumissivulle.
?>
