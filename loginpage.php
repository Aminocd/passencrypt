<?php

include "init.php";


echo <<<TEXT

<script type="text/javascript" src="cryptojs.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<div class="widget">
  <h2>Log in</h2>
  <div class="inner">
      <ul id="login">
       <form action="default.php" method="post">
        <li>
	  Username<br/>
          <input type="text" name="username" id="username"/>
        </li>
        <li>
	  Password<br/>
  	  <input type="password" name="password" id="password"/>
 	</li>
 	
 	  <input type="text" value="
TEXT;

$randsalt=createsalt(0,0);

echo $randsalt;

echo <<<TEXT
" name="salt" id="salt" style="display:none"/>
 	
        <li>
 	  <input type="submit" value="Log in" id="submit"/>
        </li>
       </form>
      </ul>
  </div>
</div>

<div id="testresults">these are the results</div>

<script src="pbkdf2.js"></script>

<script type="text/javascript">

//default pass: kianootestpass

$("form").submit(function() {
  uniqsalt=$("#salt").val();
  sessionStorage.setItem("encryptsalt",uniqsalt);
  password=$("#password");
  originalpass=password.val();
  permasalt=$("#username").val();
  sessionStorage.setItem("username",permasalt);
  firsthash=CryptoJS.PBKDF2(originalpass, permasalt, {keySize: 128/32, iterations: 2000});
  sessionStorage.setItem("password",firsthash);
  finalpass=firsthash+uniqsalt;
  finalhash=CryptoJS.SHA256(finalpass);
  password.val(finalhash);
  return true;
  });

</script>

TEXT;
?>
