<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
session_unset();
//echo(json_encode("SESSION CLOSED"));
$a='../app/hotel.html';
echo(json_encode($a));
?>