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

include 'login.php';

if (!(isset($show))) {
  header("Location: loginpage.php");
  }
else {

//create one way salt for client-side decryptior

$decryptsalt=createsalt(0,1);

//create two way salt for client-side encryption

$encryptsalt=createsalt($username);

$firsthash=getpassword($username);

$fstream = fopen("phplog.txt","a+");

?>

<html>

<head>

<style>
    /* RESET */
    html, body, div, span, h1, h2, h3, h4, h5, h6, p, blockquote, a,
    font, img, dl, dt, dd, ol, ul, li, legend, table, tbody, tr, th, td 
    {margin:0px;padding:0px;border:0;outline:0;font-weight:inherit;font-style:inherit;font-size:100%;font-family:inherit;list-style:none;}
    a img {border: none;}
    ol li {list-style: decimal outside;}
    fieldset {border:0;padding:0;}
    
    body { font-family: sans-serif; font-size: 1em; }
    
    div#container { width: -50px; margin: 0 auto; padding: 1em 0;  }
    p { margin: 1em 0; max-width: 600px; }
    h1 + p { margin-top: 0; }
    
    h1, h2 { font-family: Georgia, Times, serif; }
    h1 { font-size: 2em; margin-bottom: .75em; }
    h2 { font-size: 1.5em; margin: 2.5em 0 .5em; border-bottom: 1px solid #999; padding-bottom: 5px; }
    h3 { font-weight: bold; }
    
    ul li { list-style: disc; margin-left: 1em; }
    ol li { margin-left: 1.25em; }
    
    div.side-by-side { width: 100%; margin-bottom: 1em; }
    div.side-by-side > div { float: left; width: 50%; }
    div.side-by-side > div > em { margin-bottom: 10px; display: block; }
    
    a { color: orange; text-decoration: underline; }
    
    .faqs em { display: block; }
    
    .clearfix:after {
      content: "\0020";
      display: block;
      height: 0;
      clear: both;
      overflow: hidden;
      visibility: hidden;
    }
    
    footer {
      margin-top: 2em;
      border-top: 1px solid #666;
      padding-top: 5px;
    }

button  {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#ededed;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#575557;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	padding:2px 66px;
	text-decoration:none;
	text-shadow:1px 1px 0px #ffffff;
}button:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#dfdfdf;
}button:active {
	position:relative;
	top:1px;
}

input[type=submit]  {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#ededed;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#575557;
	font-family:arial;
	font-size:14px;
	font-weight:bold;
	padding:2px 20px;
	text-decoration:none;
	text-shadow:1px 1px 0px #ffffff;
}input[type=submit]:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#dfdfdf;
}input[type=submit]:active {
	position:relative;
	top:1px;
}

table {
  width:100%;
  }

table td {
  width:50%;
  border: 1px solid black;
  border-collapse: collapse;
  overflow: auto;
  }

  .body-class {
    margin:0px 100px;
    padding:5px;
    border:1px solid black;
    }

  .spouse_sect {
    display:none;
    }

  .spouse_underline {
    text-decoration:underline;
    }
 
  #encryptsalt {
    display:none;
    }

  </style>
  <link rel="stylesheet" href="chosen/chosen.css" />
</head>
<body class="body-class">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<?php

include 'server.php';

$mysqli = new mysqli($server,$user,$pass,$db);

$table = "clients";

$query = "SELECT * FROM $table ORDER BY userid DESC LIMIT 10";

$result = $mysqli->query($query);

if (!$result) {
  die("\ncould not retrieve records: ".$mysqli->error);
  }

$mysqli->close();

$client_counter = 0;

$clienttablestr = <<<OED
<table style="width:70%; border-collapse: collapse;border:1px solid black"><tr><th>userid</th><th>Name</th><th>Age</th><th>Years of formal education</th><th>Income</th></tr>
OED;


while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
  if ($client_counter == 0) {
    $lastuserid = $row["userid"];
    }

  list($cYear,$cMonth,$cDay) = explode("-",date("Y-m-d"));

  if ((($cmonth == $row['Birthmonth']) && ($cDay >= $row['Birthday'])) || ($cMonth > $row['Birthmonth'])) {
    $age = $cYear - $row['Birthyear'];
    }
  else {
    $age = $cYear - $row['Birthyear'] - 1;
    }

  extract($row);

  $clienttablestr .= <<<OED
<tr><td style="width:20%;max-height:100px;text-align:center">$userid</td><td style="width:20%;max-height:100px">$FullName</td><td style="width:20%;max-height:100px;text-align:center">$age</td><td style="width:20%;max-height:100px;text-align:center">$EducationYears</td><td style="width:20%;max-height:100px;text-align:center">$income</td></tr>
OED;

  $client_counter++;
  }

$clienttablestr .= "</table>";

$str=<<<TEXT
$clienttablestr<br/><br/>
userid: <input type="text" id="userid" value="$lastuserid"/><br/>

<br/>

Full Name: <input type="text" id="FullName" /><br/>
<br/>
Marriage: <label for="radio_single"><input type="radio" name="singlespouse" id="radio_single" checked="checked"/>single</label>
<label for="radio_married"><input type="radio" name="singlespouse" id="radio_married"/>married</label>
<br/><br/>
<b>Age</b><br/><br/>
<div style="position:relative">
year of birth:&nbsp;<select id="Birthyear" data-placeholder="Select the year of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
<option value=NULL></option> 
<option value="1995">1995</option>
<option value="1994">1994</option>
<option value="1993">1993</option>
<option value="1992">1992</option>
<option value="1991">1991</option>
<option value="1990">1990</option>
<option value="1989">1989</option>
<option value="1988">1988</option>
<option value="1987">1987</option>
<option value="1986">1986</option>
<option value="1985">1985</option>
<option value="1984">1984</option>
<option value="1983">1983</option>
<option value="1982">1982</option>
<option value="1981">1981</option>
<option value="1980">1980</option>
<option value="1979">1979</option>
<option value="1978">1978</option>
<option value="1977">1977</option>
<option value="1976">1976</option>
<option value="1975">1975</option>
<option value="1974">1974</option>
<option value="1973">1973</option>
<option value="1972">1972</option>
<option value="1971">1971</option>
<option value="1970">1970</option>
<option value="1969">1969</option>
<option value="1968">1968</option>
<option value="1967">1967</option>
<option value="1966">1966</option>
<option value="1965">1965</option>
<option value="1964">1964</option>
<option value="1963">1963</option>
<option value="1962">1962</option>
<option value="1961">1961</option>
<option value="1960">1960</option>
<option value="1959">1959</option>
<option value="1958">1958</option>
<option value="1957">1957</option>
<option value="1956">1956</option>
<option value="1955">1955</option>
<option value="1954">1954</option>
<option value="1953">1953</option>
<option value="1952">1952</option>
<option value="1951">1951</option>
<option value="1950">1950</option>
<option value="1949">1949</option>
<option value="1948">1948</option>
<option value="1947">1947</option>
<option value="1946">1946</option>
<option value="1945">1945</option>
<option value="1944">1944</option>
<option value="1943">1943</option>
<option value="1942">1942</option>
<option value="1941">1941</option>
<option value="1940">1940</option>
<option value="1939">1939</option>
<option value="1938">1938</option>
<option value="1937">1937</option>
<option value="1936">1936</option>
<option value="1935">1935</option>
<option value="1934">1934</option>
<option value="1933">1933</option>
<option value="1932">1932</option>
<option value="1931">1931</option>
<option value="1930">1930</option>
<option value="1929">1929</option>
<option value="1928">1928</option>
<option value="1927">1927</option>
<option value="1926">1926</option>
<option value="1925">1925</option>
<option value="1924">1924</option>
<option value="1923">1923</option>
<option value="1922">1922</option>
<option value="1921">1921</option>
<option value="1920">1920</option>
<option value="1919">1919</option>
<option value="1918">1918</option>
<option value="1917">1917</option>
<option value="1916">1916</option>
<option value="1915">1915</option>
<option value="1914">1914</option>
<option value="1913">1913</option>
<option value="1912">1912</option>
<option value="1911">1911</option>
<option value="1910">1910</option>
<option value="1909">1909</option>
<option value="1908">1908</option>
<option value="1907">1907</option>
<option value="1906">1906</option>
<option value="1905">1905</option>
<option value="1904">1904</option>
<option value="1903">1903</option>
<option value="1902">1902</option>
<option value="1901">1901</option>
<option value="1900">1900</option>
</select></div><br/>

<div style="position:relative">
month of birth:&nbsp;<select id="Birthmonth" data-placeholder="Select the month of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
<option value=NULL> </option>
<option value="1">January</option>
<option value="2">Febuary</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select></div><br/>

<div style="position:relative">
day of birth:&nbsp;<select id="Birthday" data-placeholder="Select the day of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
<option value=NULL></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select></div>

<br/><br/>Years of education<br/><input id="EducationYears" type="text" style="width:350"/>

<br/><br/>Income<br/><input id="income" type="text" style="width:350"/>

<br/><br/>

<span class="spouse_sect">
<b>Spouse's Age</b><br/><br/>

      <div style="position:relative">
        <span>year of birth:&nbsp;</span><select id="Birthyearsp" data-placeholder="Select the year of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
        <option value=NULL></option> 
	<option value="1995">1995</option>
	<option value="1994">1994</option>
	<option value="1993">1993</option>
	<option value="1992">1992</option>
	<option value="1991">1991</option>
	<option value="1990">1990</option>
	<option value="1989">1989</option>
	<option value="1988">1988</option>
	<option value="1987">1987</option>
	<option value="1986">1986</option>
	<option value="1985">1985</option>
	<option value="1984">1984</option>
	<option value="1983">1983</option>
	<option value="1982">1982</option>
	<option value="1981">1981</option>
	<option value="1980">1980</option>
	<option value="1979">1979</option>
	<option value="1978">1978</option>
	<option value="1977">1977</option>
	<option value="1976">1976</option>
	<option value="1975">1975</option>
	<option value="1974">1974</option>
	<option value="1973">1973</option>
	<option value="1972">1972</option>
	<option value="1971">1971</option>
	<option value="1970">1970</option>
	<option value="1969">1969</option>
	<option value="1968">1968</option>
	<option value="1967">1967</option>
	<option value="1966">1966</option>
	<option value="1965">1965</option>
	<option value="1964">1964</option>
	<option value="1963">1963</option>
	<option value="1962">1962</option>
	<option value="1961">1961</option>
	<option value="1960">1960</option>
	<option value="1959">1959</option>
	<option value="1958">1958</option>
	<option value="1957">1957</option>
	<option value="1956">1956</option>
	<option value="1955">1955</option>
	<option value="1954">1954</option>
	<option value="1953">1953</option>
	<option value="1952">1952</option>
	<option value="1951">1951</option>
	<option value="1950">1950</option>
	<option value="1949">1949</option>
	<option value="1948">1948</option>
	<option value="1947">1947</option>
	<option value="1946">1946</option>
	<option value="1945">1945</option>
	<option value="1944">1944</option>
	<option value="1943">1943</option>
	<option value="1942">1942</option>
	<option value="1941">1941</option>
	<option value="1940">1940</option>
	<option value="1939">1939</option>
	<option value="1938">1938</option>
	<option value="1937">1937</option>
	<option value="1936">1936</option>
	<option value="1935">1935</option>
	<option value="1934">1934</option>
	<option value="1933">1933</option>
	<option value="1932">1932</option>
	<option value="1931">1931</option>
	<option value="1930">1930</option>
	<option value="1929">1929</option>
	<option value="1928">1928</option>
	<option value="1927">1927</option>
	<option value="1926">1926</option>
	<option value="1925">1925</option>
	<option value="1924">1924</option>
	<option value="1923">1923</option>
	<option value="1922">1922</option>
	<option value="1921">1921</option>
	<option value="1920">1920</option>
	<option value="1919">1919</option>
	<option value="1918">1918</option>
	<option value="1917">1917</option>
	<option value="1916">1916</option>
	<option value="1915">1915</option>
	<option value="1914">1914</option>
	<option value="1913">1913</option>
	<option value="1912">1912</option>
	<option value="1911">1911</option>
	<option value="1910">1910</option>
	<option value="1909">1909</option>
	<option value="1908">1908</option>
	<option value="1907">1907</option>
	<option value="1906">1906</option>
	<option value="1905">1905</option>
	<option value="1904">1904</option>
	<option value="1903">1903</option>
	<option value="1902">1902</option>
	<option value="1901">1901</option>
	<option value="1900">1900</option>
</select></div><br/>

      <div style="position:relative">
        <span>month of birth:&nbsp;</span><select id="Birthmonthsp" data-placeholder="Select the month of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
	<option value=NULL> </option>
	<option value="1">January</option>
	<option value="2">Febuary</option>
	<option value="3">March</option>
	<option value="4">April</option>
	<option value="5">May</option>
	<option value="6">June</option>
	<option value="7">July</option>
	<option value="8">August</option>
	<option value="9">September</option>
	<option value="10">October</option>
	<option value="11">November</option>
	<option value="12">December</option>
</select></div><br/>

      <div style="position:relative">
        <span>day of birth:&nbsp;</span><select id="Birthdaysp" data-placeholder="Select the day of birth..." class="chzn-select-deselect" style="width:150px;" tabindex="2">
  	<option value=NULL></option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	<option value="11">11</option>
	<option value="12">12</option>
	<option value="13">13</option>
	<option value="14">14</option>
	<option value="15">15</option>
	<option value="16">16</option>
	<option value="17">17</option>
	<option value="18">18</option>
	<option value="19">19</option>
	<option value="20">20</option>
	<option value="21">21</option>
	<option value="22">22</option>
	<option value="23">23</option>
	<option value="24">24</option>
	<option value="25">25</option>
	<option value="26">26</option>
	<option value="27">27</option>
	<option value="28">28</option>
	<option value="29">29</option>
	<option value="30">30</option>
	<option value="31">31</option>
</select></div>

<br/><br/>Spouse's years of education<br/><input id="EducationYearssp" type="text" style="width:350"/>

<br/><br/>Spouse's income<br/><input id="incomesp" type="text" style="width:350"/><br/><br/>
</span>
<span id="textoutput">
<span id="encryptsalt">$encryptsalt</span>
</span>

<script src="jquery-cookie-master/jquery_cookie.js" type="text/javascript"></script>
<script src="chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>

TEXT;

$strenc=encrypt($str,$firsthash,$decryptsalt);

echo <<<TEXT
<script type="text/javascript">
var decryptsalt="$decryptsalt";

var strenc="$strenc";

</script>
<span id="decryptmessage">

</span>
TEXT;

/*we need a salt for encrypting data before sending it to the server. The server needs to know the salt.

we also need to know the username linked to that salt.

*/
}

?>
<script type="text/javascript" src="passencrypt.js"></script>
</body>
</html>






