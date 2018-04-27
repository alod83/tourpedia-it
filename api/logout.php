<?php
session_start();
session_unset();
echo(json_encode("SESSION CLOSED"));
?>