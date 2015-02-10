<?php

$mysqli=new mysqli($server,$user,$pass,$db);

if ($mysqli->connect_errno) { 
  die("Could not connect: ".$mysqli->connect_error);
  }

$table="users";

?>
