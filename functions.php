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

//archaic session related function, not used
function logged_in() {
  return (isset($_SESSION['userid'])) ? true : false;
  }

function user_exists($username) {
  global $mysqli;
  global $table;
  $username=sanitize($username);
  $query="SELECT COUNT(userid) FROM $table WHERE username='$username'";
  if ($result=$mysqli->query($query)) { 
    $result->data_seek(0);
    $row=$result->fetch_row();
    }
  else {
    echo $mysqli->error."<br/>";
    }
  
  return ($row[0]) ? true : false;
  }

function sanitize($data) {
  global $mysqli;
  return $mysqli->real_escape_string($data);
  }

function userid_from_username($username) { 
  global $mysqli;
  global $table;
  $username = sanitize($username);
  $query="SELECT userid FROM $table WHERE username='$username'";
  $result=$mysqli->query($query);
  $result->data_seek(0);
  $row=$result->fetch_row();
  return ($row[0]);
  }

function user_active($username) {
  global $mysqli;
  global $table;
  $username = sanitize($username);
  $query="SELECT COUNT(userid) FROM $table WHERE username='$username' AND active=1";
  if ($result=$mysqli->query($query)) { 
    $result->data_seek(0);
    $row=$result->fetch_row();
    }
  else {
    echo $mysqli->error."<br/>";
    }
  return ($row[0]) ? true : false;
  }

function hashpass($pass,$salt) {
  return hash("sha256",$pass.$salt);
  }
  
  
//check what userid is linked to that salt  
function userid_from_salt($salt) {
  global $mysqli;
  $table="uniquesalts";
  $salt=sanitize($salt);
  $query="SELECT COUNT(token) FROM $table WHERE token='$salt'";
  $result=$mysqli->query($query);
  $result->data_seek(0);
  $row=$result->fetch_row();
  //if at least one salt matches, find userid
  if ($row[0]) {
    //retrieve userid of matching salt
    $query="SELECT userid FROM $table WHERE token='$salt'";
    $result=$mysqli->query($query);
    $row=$result->fetch_row();
    return $row[0];
    }
  //otherwise, return a userid of 0, which gives no access
  else {
    return 0;
    }
  }
  
 
function mark_salt_as_used($salt) {
  global $mysqli;
  $table="uniquesalts";
  $query="UPDATE $table SET used=1 WHERE token='$salt'";
  $mysqli->query($query);
  }

// check if salt is in db and has not been used
function checksalt($salt, $username=NULL) {
  global $mysqli;
  $table="uniquesalts";
  $salt=sanitize($salt);
  $username=sanitize($username);
  if ($username==NULL) {
    $log="\n \$username is ".$username." userid is zero";
    $userid=0;
    }
  else {
    $userid=userid_from_username($username);
    $log="\nuserid is ".$userid;
    }
  $query="SELECT COUNT(token) FROM $table WHERE token='$salt' AND userid=$userid";
  $log.=". username is: ".$username." query is: ".$query;
  $result=$mysqli->query($query);
  $fstream = fopen("phplog.txt","a+");
  $row=$result->fetch_row();
  if ($row[0]) {
    $query="SELECT used FROM $table WHERE token='$salt' AND userid=$userid";
    $result=$mysqli->query($query);
    if ($row=$result->fetch_row()) {
      $log.="\nfetch_row() function returned something";
      }
    else {
      $log.="\nfetch_row() function didn't return an array";
      }
    $log.=" \$row[0]:  ".$row[0]." salt: ".$salt;
    if ($row[0]==0) {
   // fwrite($fstream,$log);
      mark_salt_as_used($salt);
      return true;
      }
    else {
 //     fwrite($fstream,$log);
      return false;
      }
    }
  else {
    $log.="\n empty row";
 //   fwrite($fstream,$log);
    return false;
    }
  }  
  
  
function createsalt($username=0,$oneway=0) {
  global $mysqli;
  $table="uniquesalts";
  if ($oneway != 0 && $oneway != 1) {
    die("second parameter in createsalt argument needs to be 0 or 1");
    }
  //when username argument not passed, set userid to 0, which grants the salt no access to the client table
  if ($username===0) {
    $userid=0;
    }
  //otherwise assign userid of valid user
  else {
    $userid=userid_from_username($username);
    }
  $randomsalt=md5(mt_rand().microtime());
  $query="INSERT INTO $table (token,used,userid) VALUES ('$randomsalt',$oneway,$userid)";
  $mysqli->query($query);
  return $randomsalt;
  }  

  
function getpassword($username) {
  global $mysqli;
  global $table;
  $userid=userid_from_username($username);
  $username=sanitize($username);
  $query="SELECT COUNT(userid) FROM $table WHERE username='$username'";
  $result=$mysqli->query($query);
  $row=$result->fetch_row();
  if ($row[0]) {
    $query="SELECT password FROM $table WHERE username='$username'";
    $result=$mysqli->query($query);
    $row=$result->fetch_row();
    $dbpassword=$row[0];
    }
  else {
    return false;
    } 
  return $dbpassword;
  }

function login($username, $password, $salt) {
  global $mysqli;
  global $table;
  if (!$dbpassword=getpassword($username)) {
    return false;
    }   
  $dbpassword=hashpass($dbpassword,$salt); 
  return ($dbpassword == $password) ? true : false;
  }
  
  
function encrypt($message,$password,$salt) {
  $key=$password.$salt;
  $encrypted=GibberishAES::enc($message,$key);
  $fstream = fopen("phplog.txt","a+");
  //$log="\nencrypted results: ".$encrypted."\n";
  // fwrite($fstream,$log);
  return $encrypted;
  }


function decrypt($message,$password,$salt) {
  $key=$password.$salt;
  $decrypted=GibberishAES::dec($message,$key);
  return $decrypted;
  }

?>
