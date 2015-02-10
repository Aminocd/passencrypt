<?php

include "init.php";

if (empty($_POST) === false) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $salt = $_POST['salt'];
    if (empty($username) === true || empty($_POST) === true) {
      $errors[]="You need to enter a username and password";
      }
    else if (user_exists($username) === false) {  
      $errors[]="We can't find that username. Have you registered?";
      }
    else if (user_active($username) === false) {
      $errors[]="You haven't activated your account";
      }
    else {
      if (checksalt($salt) === false) {
        $errors[] = "the token is expired";
        }
      else {
        $login = login($username, $password, $salt);
        if ($login === false) {
          $errors[] = "That username/password combination is incorrect";
          }
        else {
          // set the user POST
          $_POST['userid'] = $login;
          /*
          * //redirect user to home
          *header("Location: default.php");
          *exit();
          */
          $show=1;
          }
        }
      }

  } 
else {
  $errors[]="\$_POST is empty";
  }

//print_r($errors);


?>
