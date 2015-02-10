<?php

/**
 * Copyright 2015 kianoo.com
 *
 * Licensed under the MIT license (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/mit-license.php
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

include 'server.php';

$fstream=fopen("phplog.txt","a+");

$mysqli=new mysqli($server,$user,$pass,$db);

if ($mysqli->connect_errno) {
  die("Could not connect: ".$mysqli->connect_error);
  }

$table="clients";

extract($_POST);

//declare field names:

$field1=$FullName;

$field2=$Age;

$field3=$Gender;

$field4=$married;

$field5=$income;

$field6=$EducationYears;

$field7=$Agesp;

$field8=$incomesp;

$field9=$EducationYearssp;

//declare and sanitize for MySQL user input

//FullName

$field1value=$mysqli->real_escape_string($field1);

//Age

$field2value=$mysqli->real_escape_string($field2);

preg_match_all('!\d+!',$field2value,$matches);

$age=$matches[0][0];

$year=date("Y");

$month=date("m")-1;

$field2value_Y=$year-$age;

$field2value_M=$month-6;

if ($field2value_M<0) {
  $field2value_M+=12;
  $field2value_Y-=1;
  }

$field2value_M+=1;

$field2value_D=date("d");

//Gender

$field3value=$mysqli->real_escape_string($field3);

//married

$field4value=$mysqli->real_escape_string($field4);

switch ($field4value) {

  case "Single":

    $field4value = "0";
 
    break;

  case "Married":

  case "Engaged":

    $field4value = "1";
  
    break;

  default: 

    $field4value = "0";

  }

//income

$field5value=$mysqli->real_escape_string($field5);

//EducationYears

$field6value=$mysqli->real_escape_string($field6);

preg_match_all('!\d+!', $field6value, $matches2);

$field6value=$matches2[0][0];

if (empty($field6value)) {
  $field6value=0;
  }

//Agesp

$field7value=$mysqli->real_escape_string($field7);

preg_match_all('!\d+!',$field7value,$matches3);

$age=$matches3[0][0];

$year=date("Y");

$month=date("m")-1;

$field7value_Y=$year-$age;

$field7value_M=$month-6;

if ($field7value_M<0) {
  $field7value_M+=12;
  $field7value_Y-=1;
  }

$field7value_M+=1;

$field7value_D=date("d");

//incomesp

$field8value=$mysqli->real_escape_string($field8);

//EducationYearssp

$field9value=$mysqli->real_escape_string($field9);

preg_match_all('!\d+!', $field9value, $matches4);

$field9value=$matches4[0][0];

if (empty($field9value)) {
  $field9value=0;
  }

$column1="FullName";

$column2_Y="BirthYear";

$column2_M="Birthmonth";

$column2_D="Birthday";

$column3="Gender";

$column4="married";

$column5="income";

$column6="EducationYears";

$column7_Y="Birthyearsp";

$column7_M="Birthmonthsp";

$column7_D="Birthdaysp";

$column8="incomesp";

$column9="EducationYearssp";

$query="INSERT INTO $table($column1,$column2_Y,$column2_M,$column2_D,$column3,$column4,$column5,$column6,$column7_Y,$column7_M,$column7_D,$column8,$column9) VALUES ('$field1value','$field2value_Y','$field2value_M','$field2value_D','$field3value','$field4value','$field5value','$field6value',$field7value_Y,$field7value_M,$field7value_D,'$field8value','$field9value')";

$result=$mysqli->query($query);

$fstream = fopen("phplog.txt","a+");
fwrite($fstream,"\n\nINSERTED with query: $query\n\n");

if (!$result)
  {
  $fstream = fopen("phplog.txt","a+");
  fwrite($fstream,"could not add record: ".$query."\n".$mysqli->error."\n");
  die("could not add record: ".$mysqli->error);
  }
$mysqli->close();

?>
