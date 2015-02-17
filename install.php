<?php

include 'createtables.php';

// Required field names
$required = array('username', 'password');

// Loop over field names, make sure each one exists and is not empty
$error = false;
foreach($required as $field) {
  if (empty($_POST[$field])) {
    $error = true;
  }
}


if ($error) {
echo <<<EOD

<script type="text/javascript" src="cryptojs.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


Enter the username and password of the user (warning, if not accessing website through a local-ip, the password-derived key will be transmitted in plaintext over the internet:<br/><br/>

<form action="#" method="post">
 <p>Username: <input type="text" name="username" id="username"/></p>
 <p>Password: <input type="text" name="password" id="password"/></p>
 <p><input type="submit" name="submit" id="submit"/></p>
</form>

<script src="pbkdf2.js"></script>

<script type="text/javascript">

//default pass: kianootestpass

$("form").submit(function() {
  password=$("#password");
  originalpass=password.val();
  permasalt=$("#username").val();
  firsthash=CryptoJS.PBKDF2(originalpass, permasalt, {keySize: 128/32, iterations: 2000});
  password.val(firsthash);
  return true;
  });

</script>
 
EOD;
  
  }
else {
  $username = $_POST['username'];

  $password = $_POST['password'];

  run_create_table();

  //see if user already exists

  $tablename = "users";
 
  $log_user_input = "<br/>";

  $num_rows = runquery($tablename,TRUE);
 
  $username = sanitize($username);

  $password = sanitize($password);

  if ($num_rows) {
    $log_user_input .= "a username and password are already in the `user` table</br>";
    }
  else {
    $query = "INSERT INTO $tablename (username, password, active) VALUES('$username', '$password', 1)";
    $mysqli->query($query);
    $log_user_input .= "username and password you chose have been inputted!</br>The username/password you are:<br/>username: $username<br/>password: $password<br/>";
    }
  echo $log_user_input."<br/>the site is ready to be used!";
  }

?>
