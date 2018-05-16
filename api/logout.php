<?php
session_start();
session_unset();
//echo(json_encode("SESSION CLOSED"));
$a='../app/hotel.html';
echo(json_encode($a));
?>