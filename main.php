<?php
session_start();
include("./simple_html_dom.php");
include("controllers/page.php");
include("controllers/ota.php");

ini_set('memory_limit', '-1');
// Create DOM from URL or file
$page = new GetPage();
$url = "https://www.pilotfishtechnology.com/modelviewers/OTA/model/";
$start = $page->downloadPage($url . "Format.OTA_VehAvailRateRQ.html");
$html = str_get_html($start);

//print_r($html->find("BODY")[0]);
$ota = new otaCrawler();
$ota->getContent($html, "OTA_VehAvailRateRQ");
echo(json_encode($_SESSION));
?>