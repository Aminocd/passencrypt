<?php

include 'init.php';

extract($_GET);

$fstream = fopen("phplog.txt","a+");

$invalid = false;

if (userid_from_username($username)==0) {
  $errorstr = "\ninvalid token provided (username), please try again\n";
  $invalid = true;
  }

if (!checksalt($encryptsalt,$username)) {
  $errorstr = "\ninvalid token provided (salt used), please try again. salt: $encryptsalt username: $username\n";
  $invalid = true;
  }

if ($invalid) [
  fwrite($fstream, $errorstr);
  die($errorstr);
} else {
  fwrite($fstream, "\nusername and salt worked!\n");
}

fclose($fstream);

$firsthash=getpassword($username);

$userid=decrypt($enc_userid,$firsthash,$encryptsalt);

// create two way salt for client-side encryption

$encryptsalt = createsalt($username);

// create one way salt for client-side decryption

$decryptsalt = createsalt(0,1);

$mysqli = new mysqli($server, $user, $pass, $db);

$table = "clients";

$query = "SELECT * FROM $table WHERE userid=$userid";

$result = $mysqli->query($query);

if (!$result) {
  die("\ncould not add record: ".$mysqli->error);
  }

$mysqli->close();

$row = $result->fetch_array(MYSQLI_ASSOC);

$row["encryptsalt"] = $encryptsalt;

$contact_json = json_encode($row);

$enc_contact = encrypt($contact_json, $firsthash, $decryptsalt);

$returnarray=array($enc_contact,$decryptsalt);

$returnjson = json_encode($returnarray);

echo $returnjson;

?>
