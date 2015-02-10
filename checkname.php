<?php

include 'server.php';

$mysqli=new mysqli($server,$user,$pass,$db);

if ($mysqli->connect_errno) {
  die("Could not connect: ".$mysqli->connect_error);
  }

$table="clients";

extract($_POST);

//declare field names:

$field1=$FullName;

$field2=$income;

$field1value=$mysqli->real_escape_string($field1);

$field2value=$mysqli->real_escape_string($field2);

$column1 = "FullName";

$column2 = "income";

$query="SELECT * FROM $table WHERE $column1='$field1value' AND $column2='$field2value'";

$result=$mysqli->query($query);

$number=$result->num_rows;
 
$fstream = fopen("phplog.txt","a+");

$towrite = $number;

if ($number == 0) {
  fwrite($fstream,"query: $query \nnumber of rows: $towrite\n\nInsert\n\n");
  echo 1;
  return 0; 
  }
else {  
  fwrite($fstream,"query: $query \nnumber of rows: $towrite\n\n");
  echo 0;
  return 0;
  }
//fwrite($fstream,"query: ".$query."\n");


//echo "query: $query >>>> number of rows: $towrite<br/><br/>";

?>
