<?php

define("EDUCATION_COEFFICIENT",5);
define("INCOME_COEFFICIENT",5);
define("EDUCATION_SPOUSE_MODIFIER",50);
define("INCOME_SPOUSE_MODIFIER",67);

include 'init.php';

extract($_GET);

$fstream = fopen("phplog.txt","a+");

function exception_handler($exception) {
  global $fstream;
  fwrite($fstream,"Uncaught exception: " . $exception->getMessage()."\n");
  }

set_exception_handler("exception_handler");

function error_handler($errno, $errstr, $errfile, $errline) {
  global $fstream;
  {
    if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting
      return;
      }

    switch ($errno) {
    case E_USER_ERROR:
        $errorstring.= "<b>My ERROR</b> [$errno] $errstr<br />\n";
        $errorstring.= "  Fatal error on line $errline in file $errfile";
        $errorstring.= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        $errorstring.= "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        $errorstring.= "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        $errorstring.= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        $errorstring.= "Unknown error type: [$errno] $errstr line: $errline<br />\n";
        break;
    }

    fwrite($fstream,$errorstring);  

    /* Don't execute PHP internal error handler */
    return true;
    }
  }

set_error_handler("error_handler");

if (userid_from_username($username)==0) {
//  fwrite($fstream,"invalid token provided (username), please try again\n");
  die("invalid token provided (username), please try again");
  }

if (!checksalt($encryptsalt,$username)) {
//  fwrite($fstream,"invalid token provided (salt used), please try again. salt: $encryptsalt username: $username\n");
  die("invalid token provided (salt used), please try again. salt: $encryptsalt username: $username");
  }

$firsthash=getpassword($username);

$dectext=decrypt($enctext,$firsthash,$encryptsalt);

// create two way salt for client-side encryption

$encryptsalt = createsalt($username);

// create one way salt for client-side decryption
 
$decryptsalt = createsalt(0,1);

$textarray=json_decode($dectext,true);

extract($textarray);

function age($bMonth,$bDay,$bYear) {
  list($cYear,$cMonth,$cDay)=explode("-",date("Y-m-d"));
  return ( ($cMonth == $bMonth && $cDay >= $bDay) || ($cMonth > $bMonth) ) ? $cYear - $bYear : $cYear - $bYear - 1;
  }

//age component of score

$age = age($Birthmonth,$Birthday,$Birthyear);

$agesp = age($Birthmonthsp,$Birthdaysp,$Birthyearsp);

if ($age > 17 && $age < 21) {
  $score=150;
  }
elseif ($age >= 21 && $age < 25) {
  $score=180;
  }
elseif ($age >= 25 && $age < 28) {
  $score=220;
  }
elseif ($age >= 28 && $age < 34) {
  $score=240;
  }
elseif ($age >= 34 && $age < 40) {
  $score=280;
  }
elseif ($age >= 40 && $age < 50) {
  $score=300;
  }
elseif ($age >= 50 && $age < 60) {
  $score=290;
  }
elseif ($age >= 60 && $age < 70) {
  $score=250;
  }
elseif ($age >= 70 && $age < 80) {
  $score=210;
  }
elseif ($age >= 80) {
  $score=370-$age*2;
  }
else {
  $score=0;
  }

if ($agesp > 17 && $agesp < 21) {
  $score+=75;
  }
elseif ($agesp >= 21 && $agesp < 25) {
  $score+=90;
  }
elseif ($agesp >= 25 && $agesp < 28) {
  $score+=110;
  }
elseif ($agesp >= 28 && $agesp < 34) {
  $score+=120;
  }
elseif ($agesp >= 34 && $agesp < 40) {
  $score+=140;
  }
elseif ($agesp >= 40 && $agesp < 50) {
  $score+=150;
  }
elseif ($agesp >= 50 && $agesp < 60) {
  $score+=145;
  }
elseif ($agesp >= 60 && $agesp < 70) {
  $score+=125;
  }
elseif ($agesp >= 70 && $agesp < 80) {
  $score+=105;
  }
elseif ($agesp >= 80 && $agesp < 120) {
  $score+=185-$agesp;
  }
else {
  $score+=0;
  }

//education and income component of score

$score+=$EducationYears*EDUCATION_COEFFICIENT;

if ($married == 0) {
  $score+=$income/1000*INCOME_COEFFICIENT;
  }
else {
  $score+=$EducationYearssp*EDUCATION_COEFFICIENT*(100/EDUCATION_SPOUSE_MODIFIER);
  $score+=$income/1000*INCOME_COEFFICIENT*(100/INCOME_SPOUSE_MODIFIER);
  }

$score=number_format($score,0);

$returnmessage.="Their credit score is $score";

$mysqli=new mysqli($server,$user,$pass,$db);

if ($mysqli->connect_errno) { 
  fwrite($fstream,"Could not connect: ".$mysqli->connect_error);
  }

$table="clients";

$variables=array($userid,$FullName,$Birthyear,$Birthmonth,$Birthday,$income,$EducationYears,$married,$Birthyearsp,$Birthmonthsp,$Birthdaysp,$incomesp,$EducationYearssp);

//sanitize MySQL inputs

foreach ($variables as &$variable) {
  $variable=$mysqli->real_escape_string($variable);
  }

//assign values of array elements to new variables

list($userid,$FullName,$Birthyear,$Birthmonth,$Birthday,$income,$EducationYears,$married,$Birthyearsp,$Birthmonthsp,$Birthdaysp,$incomesp,$EducationYearssp)=$variables;

//construct query string segments

$query_main_begin=<<<EOD
UPDATE $table SET FullName='$FullName',Birthyear=$Birthyear,Birthmonth=$Birthmonth,Birthday=$Birthday,income=$income,EducationYears=$EducationYears,married=$married
EOD;

$query_spouse_sect = <<<EOD
,Birthyearsp=$Birthyearsp,Birthmonthsp=$Birthmonthsp,Birthdaysp=$Birthdaysp,incomesp=$incomesp,EducationYearssp=$EducationYearssp
EOD;

$query_main_end = <<<EOD
 WHERE userid=$userid
EOD;

//construct query string

if ($married==1) {
$query=$query_main_begin.$query_spouse_sect.$query_main_end;
  }
else {
$query=$query_main_begin.$query_main_end;
  }

//execute query

$result=$mysqli->query($query);

if (!$result) {
  fwrite($fstream,$query."\n");
  fwrite($fstream,"could not add record: ".$mysqli->error."\n");
  }
else {
  fwrite($fstream,"the write worked!\n");
  }

$mysqli->close();

$returnmessage.= <<<TEXT
<span id="encryptsalt">$encryptsalt</span>
TEXT;

$returnmessage = encrypt($returnmessage, $firsthash, $decryptsalt);

$returnarray = array($returnmessage,$decryptsalt);

$returnjson = json_encode($returnarray);

echo $returnjson;

?>
