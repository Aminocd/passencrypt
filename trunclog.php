<?php

$logfile = "phplog.txt";

$limit = 10000;

$filesize = filesize($logfile);

if ($filesize > $limit) {
  // find file position limit bytes before end of file

  $segmentlength = $filesize - $limit;

  // minus 1 to account for change of first number from 1 to 0

  $segmentpos = $segmentlength - 1;
 
  // get content from limit bytes before end of file to end of file

  $segment = file_get_contents($logfile, false, NULL, $segmentpos, $limit);

  // truncate log file then write segment to it

  $fstream = fopen($logfile,"w+");

  fwrite($fstream,$segment);
  
  fclose($fstream);
  }

echo $filesize;

?>
