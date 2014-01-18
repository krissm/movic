<?php
//to enable debugging since xdebug adds a ?string which mess up the request
$re = $_SERVER["REQUEST_URI"];
$queryP = strpos ( $re, '?' );
$scrips = $_SERVER["SCRIPT_NAME"];
$scripL = strlen($scrips);
if ($queryP == $scripL) {
	header('Location: http://localhost/movic/guestbook');
	exit;
}
//"bootstrap" är initieringsfasen där de oundvikliga grunderna etableras och defineras. Dessa behövs i varje förfrågan. 
//---------------------------------------------------------------------------------------
define('MOVIC_INSTALL_PATH', dirname(__FILE__));
define('MOVIC_SITE_PATH', MOVIC_INSTALL_PATH . '/site'); //det är i denna katalog som 
//användaren lägger all sin egen kod som utökar ramverkets standardkod. 
//Det blir alltså en katalog för själva applikationen eller webbplatsen/siten.

require(MOVIC_INSTALL_PATH.'/src/Cmovic/bootstrap.php');

$mo = Cmovic::Instance(); //skapas och initieras $mo som blir ett globalt objekt som är kärnan 
//i ramverket. Via variabeln $mo kan man alltid nå det som behövs.

// PHASE: FRONTCONTROLLER ROUTE
//"frontController->route" tar hand om förfrågan och tolkar ut vilken kontroller och metod 
//som skall anropas. Därefter sker all bearbetning i kontrollern. 
//---------------------------------------------------------------------------------------
$mo->FrontControllerRoute();

// PHASE: THEME ENGINE RENDER
//"themeEngine->render" skapar själva slutresultatet, webbsidan. Allt innehåll finns 
//tillgängligt och med hjälp av template-filer överförs innehållet till HTML-filer.
//---------------------------------------------------------------------------------------
$mo->ThemeEngineRender();
