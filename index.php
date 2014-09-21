<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once("app/views/v_html.php");
require_once("app/controllers/c_login.php");

$htmlBody = "<h1>Laborationskod mn22nw</h1>";

/*---------------------------------------------------------------
 	-creates new instance of LoginController 
    -runs doLogin, a function that returns html-code to be put in body of HTML
----------------------------------------------------------------- */
$lc = new \controllers\LoginController();
$htmlBody .= $lc->doLogin();
// ---------------------------------//
$title = "Labb 2";
$head = '<link rel="stylesheet" type="text/css" href="css/login.css">';

setlocale(LC_TIME, 'swedish'); 
date_default_timezone_set('Europe/Stockholm');
$date = ucfirst(strftime("%A, den %d %B år %Y. Klockan är [%H:%M:%S]."));
$htmlBody .=$date;

$view = new HTMLView(); 

$view->echoHTML($title, $head, $htmlBody, $date); 

 
 
 
 