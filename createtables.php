<?php

include 'server.php';

include 'functions-install.php';

$mysqli = new mysqli($server,$user,$pass,$db);

// executable body of program

function run_create_table() {

global $mysqli;
// `users`

$tablename = "users";

$num_rows = runquery($tablename);

$query = <<<EOD
CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) DEFAULT NULL,
  `password` text,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
EOD;

$result = $mysqli->query($query);

if (!$result) {
  die("could not connect to database");  
  }

$log = reportresults($num_rows,$tablename);

// `clients`

$tablename = "clients";

$num_rows = runquery($tablename);

$query = <<<EOD
CREATE TABLE IF NOT EXISTS `clients` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `FullName` varchar(40) DEFAULT NULL,
  `Gender` varchar(15) DEFAULT NULL,
  `married` varchar(15) DEFAULT NULL,
  `income` int(11) unsigned DEFAULT NULL,
  `incomesp` int(11) unsigned DEFAULT NULL,
  `EducationYears` int(11) unsigned DEFAULT NULL,
  `EducationYearssp` int(11) unsigned DEFAULT NULL,
  `Birthyear` int(11) DEFAULT NULL,
  `Birthmonth` int(11) DEFAULT NULL,
  `Birthday` int(11) DEFAULT NULL,
  `Birthyearsp` int(11) DEFAULT NULL,
  `Birthmonthsp` int(11) DEFAULT NULL,
  `Birthdaysp` int(11) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;
EOD;

$result = $mysqli->query($query);

if (!$result) {
  die("could not connect to database");  
  }

$log .= reportresults($num_rows,$tablename);

// `uniquesalts`

$tablename = "uniquesalts";

$num_rows = runquery($tablename);

$query = <<<EOD
CREATE TABLE IF NOT EXISTS `uniquesalts` (
  `SID` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` text,
  `used` int(10) unsigned NOT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`SID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
EOD;

$result = $mysqli->query($query);

if (!$result) {
  die("could not connect to database");  
  }

$log .= reportresults($num_rows,$tablename);

echo $log;
  }

?>

