<?php
// functions for checking if tables exist, have data, and logging findings

function createquery_exists($tablename) {

  $query = "SHOW TABLES LIKE '$tablename'";
  
  return $query;
  }

function createquery_filled($tablename) {
  
  $query = "SELECT * FROM $tablename";

  return $query;
  }

function reportresults($num_rows, $tablename) {
  if ($num_rows == 1) {
    $log = "table `$tablename` already exists<br/>";
    }
  else {
    $log = "table `$tablename` created<br/>";
    }
  return $log;
  }

function runquery ($tablename, $mode=0) {

  global $mysqli; 

  if ($mode==0) {
    $query = createquery_exists($tablename);
    } 
  else {
    $query = createquery_filled($tablename);
    }

  $result = $mysqli->query($query);

  $num_rows = $result->num_rows;

  return $num_rows;
  }

// sanitize, duplicated in functions.php

function sanitize($data) {
  global $mysqli;
  return $mysqli->real_escape_string($data);
  }

?>
