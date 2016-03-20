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



function encvariables() {
  varstring=JSON.stringify(contactvars);
  encryptkey=password+encryptsalt;
  return GibberishAES.enc(varstring,encryptkey);
  }

function setvariables() {

  contactvars={};

  contactvars.userid=$("#userid").val();
 
  contactvars.FullName=$("#FullName").val();

  contactvars.income=$("#income").html();
 
  contactvars.EducationYears=$("#EducationYears").val();

  contactvars.Birthyear=$("#Birthyear").val();

  contactvars.Birthmonth=$("#Birthmonth").val();
 
  contactvars.Birthday=$("#Birthday").val();

  if ($("input[type='radio']#radio_married").is(":checked")) {
    contactvars.married=1;
    }
  else {
    contactvars.married=0;
    }

  if (contactvars.married==1) {

    contactvars.incomesp=$("#incomesp").val();
 
    contactvars.EducationYearssp=$("#EducationYearssp").val();

    contactvars.Birthyearsp=$("#Birthyearsp").val();

    contactvars.Birthmonthsp=$("#Birthmonthsp").val();
 
    contactvars.Birthdaysp=$("#Birthdaysp").val();
    }
 
  enctext=encvariables();

  }

function setcontact(plaintext) {

  contactobj = JSON.parse(plaintext);

//  $("#FullName").html(contactobj.FullName);
 
  $("#income").html(contactobj.income);

  console.log("FullName: "+contactobj.FullName);

// declare radio variables

  var $radio = $("input:radio[name=singlespouse]");

  if (contactobj.married == 0) {
    // set married radio button to single
    $radio.filter("#radio_single").attr("checked",true);
    $(".spouse_sect").toggle(false);
    }
  else {
    // set married radio button to married
    $radio.filter("#radio_married").attr("checked",true);
    $(".spouse_sect").toggle(true);
    }

  for (var name in contactobj) { 

 //   proptype = $("#userid").attr("type");  

    var selector =  $("#" + name);

    var tagName = selector.prop("tagName");

    if (tagName == "SELECT") {

// if value of object property is NULL, set to ""
    if (contactobj[name] == null) {
      contactobj[name] = "";
      console.log(name + " is null");
      }

// with chrome the closing square bracket needs to be omitted for the jQuery further down to work, so will make a variable close and ommit the square closing bracket if the Chrome browser is detected

var closingBracket;

if ($.browser.webkit) {
  closingBracket = "'";
  }
else {
  closingBracket = "']";  
  }

// select option element with value that matches contactobj property value
        $("#" + name + " option[value='"+contactobj[name]+closingBracket).attr("selected", "selected");    
// select option element with id value that matches contactobj property value
        $("#" + name + " option[id='"+contactobj[name]+closingBracket).attr("selected", "selected");    

        $("#" + name).trigger("liszt:updated");
      }

    else if (tagName == "TEXTAREA") {
      selector.val(contactobj[name]);        
      }
    else {
      proptype = selector.attr("type");
    
        if (proptype == "text") {

          selector.val(contactobj[name]);        
          }
        else if (proptype == "checkbox") {
          if (contactobj[name] == 1) {
            selector.attr("checked", "checked");
            }
          else {
            selector.removeAttr("checked");
            }
          }
      }
    }    

  setsalt(contactobj.encryptsalt);
  encryptsalt=$("#encryptsalt").html();

  newscore();  
  }

function setsalt(encryptsalt) {
  $("#encryptsalt").html(encryptsalt);
  }

function loadcontact() {

  userid = $("#userid").val();

  load_from_id(userid);
  }

function load_from_id(row_user_id) {
  userid = row_user_id;

  encryptkey=password+encryptsalt;

  enc_userid = GibberishAES.enc(userid, encryptkey);

  $.get("loadcontact.php",{enc_userid: enc_userid, encryptsalt: encryptsalt, username: username},function(contactdata) {

    var contactdata = JSON.parse(contactdata);

    decryptkey = password+contactdata[1];

    plaintext = GibberishAES.dec(contactdata[0],decryptkey);

    setcontact(plaintext);

    });

  }

function newscore() {

  setvariables();

  $.get("formulate.php",{enctext: enctext, encryptsalt: encryptsalt, username: username},function(encresponse) {

    var encobj = JSON.parse(encresponse);

    decryptkey = password+encobj[1];   

    plaintext = GibberishAES.dec(encobj[0],decryptkey); 

    $("#textoutput").html(plaintext);

    });
  }

function showpasswords() {

  var decryptkey=password+decryptsalt;
  
  strdec=GibberishAES.dec(strenc,decryptkey);
   
  $("#decryptmessage").html(strdec);

  }

$(document).ready(function () {

password=sessionStorage.getItem("password");

username=sessionStorage.getItem("username");

sessionStorage.removeItem("password");

sessionStorage.removeItem("username");

sessionStorage.removeItem("encryptsalt");

$.getScript('gibberish-aes.js',function () {
  showpasswords();
  });

setTimeout(function() {encryptsalt=$("#encryptsalt").html();loadcontact();},2000);

$("body").on("change","#userid",function(){
  encryptsalt=$("#encryptsalt").html();
  loadcontact();
  });

$("body").on("change", "select", function() {
  encryptsalt=$("#encryptsalt").html();
  newscore();
  })
  .on("click","input:radio",function(){
  encryptsalt=$("#encryptsalt").html();
  newscore();
  })
  .on("change","#FullName",function(){
  encryptsalt=$("#encryptsalt").html();
  newscore();
  })
  .on("change","#EducationYears",function(){
  encryptsalt=$("#encryptsalt").html();
  newscore();
  })
  .on("change","#EducationYearssp",function(){
  encryptsalt=$("#encryptsalt").html();
  newscore();
  })
  .on("change","#incomesp",function(){
  encryptsalt=$("#encryptsalt").html();
  newscore();
  });

$("body").on("change","input[type=radio]", function() {
  newscore();
  });

$("body").on("change","#radio_married", function() {
  $(".spouse_sect").toggle(true);
  });

$("body").on("change","#radio_single", function() {
  $(".spouse_sect").toggle(false);
  });

$("body").on("click", ".client_row", function() {
  var row_userid = $(this).find("td:eq(0)").html();
  encryptsalt=$("#encryptsalt").html();
  console.log("row 0 clicked: " + row_userid);
  load_from_id(row_userid);
  });

$("body").on("hover", ".client_row", function() {
  $(this).toggleClass("hover_client_row");
  });

$("body").on("click", ".client_row", function() {
  var time = 500;
  var this_row = $(this);
  this_row.toggleClass("click_client_row");
  setTimeout(function() {
    this_row.toggleClass("click_client_row");
    }, time);
  });
});
